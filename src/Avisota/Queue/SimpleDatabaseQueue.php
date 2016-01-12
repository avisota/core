<?php

/**
 * Avisota newsletter and mailing system
 *
 * PHP Version 5.3
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota-core
 * @license    LGPL-3.0+
 * @link       http://avisota.org
 */

namespace Avisota\Queue;

use Avisota\Event\PostTransportMessageEvent;
use Avisota\Event\PreTransportMessageEvent;
use Avisota\Message\MessageInterface;
use Avisota\Message\NativeMessage;
use Avisota\Transport\TransportInterface;
use Avisota\Transport\TransportStatus;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Statement;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * A simple single threaded queue storing the messages in a small database table.
 *
 * @package Avisota\Queue
 */
class SimpleDatabaseQueue
    implements MutableQueueInterface, EventEmittingQueueInterface, LoggingQueueInterface
{
    static public function createTableSchema($tableName)
    {
        $schema = new Schema();
        $table  = $schema->createTable($tableName);
        $table->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
        $table->addColumn('enqueue', 'datetime');
        $table->addColumn('message', 'text');
        $table->addColumn('delivery_date', 'datetime', array('notnull' => false));
        $table->setPrimaryKey(array('id'));
        return $schema;
    }

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var string
     */
    protected $tableName;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * @param NativeMessage $messageSerializer      The message serializer.
     * @param Connection    $connection             The database connection.
     * @param string        $tableName              The name of the database table.
     * @param bool          $createTableIfNotExists Create the table if not exists.
     */
    public function __construct(
        Connection $connection,
        $tableName,
        $createTableIfNotExists = false,
        LoggerInterface $logger = null,
        EventDispatcher $eventDispatcher = null
    ) {
        $this->connection      = $connection;
        $this->tableName       = (string) $tableName;
        $this->logger          = $logger;
        $this->eventDispatcher = $eventDispatcher;

        $schemaManager = $this->connection->getSchemaManager();
        if (!$schemaManager->tablesExist($this->tableName)) {
            if ($createTableIfNotExists) {
                $schema  = static::createTableSchema($this->tableName);
                $queries = $schema->toSql($this->connection->getDatabasePlatform());
                foreach ($queries as $query) {
                    $this->connection->exec($query);
                }
            } else {
                throw new \RuntimeException('The queue table ' . $this->tableName . ' does not exists!');
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setEventDispatcher(EventDispatcher $eventDispatcher = null)
    {
        $this->eventDispatcher = $eventDispatcher;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function setLogger(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        return $this->length() == 0;
    }

    /**
     * {@inheritdoc}
     */
    public function length()
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        /** @var Statement $statement */
        $statement = $queryBuilder
            ->select('COUNT(q.id)')
            ->from($this->tableName, 'q')
            ->execute();

        return (int) $statement->fetchColumn();
    }

    /**
     * {@inheritdoc}
     */
    public function getMessages()
    {
        /** @var Statement $statement */
        $statement = $this->connection
            ->createQueryBuilder()
            ->select('q.message')
            ->from($this->tableName, 'q')
            ->execute();

        $serializedMessages = $statement->fetchAll(\PDO::FETCH_COLUMN, 0);
        $messages           = array_map('unserialize', $serializedMessages);

        return $messages;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(TransportInterface $transport, ExecutionConfig $config = null)
    {
        $timeout = $config && $config->getTimeLimit() > 0
            ? time() + $config->getTimeLimit()
            : PHP_INT_MAX;

        $resultSet = $this->selectRecords($config);

        $results = array();

        if ($config) {
            $decider = $config->getDecider();
        } else {
            $decider = null;
        }

        /**
         * Initialise transport system
         */
        $transport->initialise();

        $duration = 0;
        while (count($resultSet) && (time() + $duration) < $timeout) {
            $record = array_shift($resultSet);

            $duration = time();
            $status   = $this->transport($transport, $decider, $record);
            $duration = time() - $duration;

            if ($status) {
                $results[] = $status;
                $this->connection->delete($this->tableName, array('id' => $record['id']));
            }
        }

        /**
         * Flush transport
         */
        $transport->flush();

        return $results;
    }

    /**
     * @return array[]
     */
    protected function selectRecords(ExecutionConfig $config = null)
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->select('q.*')
            ->from($this->tableName, 'q');

        if ($config) {
            if ($config->getMessageLimit() > 0) {
                $queryBuilder->setMaxResults($config->getMessageLimit());
            }
        }

        /** @var Statement $statement */
        $statement = $queryBuilder->execute();
        $resultSet = $statement->fetchAll();

        return $resultSet;
    }

    /**
     * @param array $record
     *
     * @return MessageInterface
     */
    protected function deserializeMessage(array $record)
    {
        try {
            return unserialize($record['message']);
        } catch (\Exception $e) {
            // log failed transport
            if ($this->logger) {
                $this->logger->error(
                    sprintf(
                        'Could not deserialize message "%s": %s',
                        $record['message'],
                        $e->getMessage()
                    )
                );
            }

            $this->connection->delete($this->tableName, array('id' => $record['id']));

            return false;
        }
    }

    /**
     * Do the transport of the message and create a status information object.
     *
     * @param TransportInterface $transport
     * @param MessageInterface   $message
     *
     * @return TransportStatus
     */
    protected function transport(
        TransportInterface $transport,
        ExecutionDeciderInterface $decider = null,
        $record
    ) {
        if ($record['delivery_date']) {
            $deliveryDate = \DateTime::createFromFormat('Y-m-d H:i:s', $record['delivery_date']);
            if ($deliveryDate->getTimestamp() > time()) {
                return false;
            }
        }

        $message = $this->deserializeMessage($record);

        // skip message
        if (!$message || $decider && !$decider->accept($message)) {
            return false;
        }

        // log pre transport
        $this->logPreTransportStatus($transport, $message);

        try {
            // try to transport message
            $status = $transport->send($message);

            // log successful transport
            $this->logSuccessfulStatus($transport, $message, $status);
        } catch (\Exception $e) {
            $status = new TransportStatus(
                $message,
                0,
                array_merge(
                    (array) $message->getRecipients(),
                    (array) $message->getCopyRecipients(),
                    (array) $message->getBlindCopyRecipients()
                ),
                $e
            );

            // log failed transport
            $this->logFailedStatus($transport, $message, $status);
        }

        return $status;
    }

    protected function prepareRecipientsForLogging(array $recipients)
    {
        $recipientNames = array();
        foreach ($recipients as $email => $name) {
            if (is_numeric($email)) {
                $recipientNames[] = $name;
            } else {
                $recipientNames[] = $email;
            }
        }
        return implode(', ', $recipientNames);
    }

    protected function logPreTransportStatus(TransportInterface $transport, MessageInterface $message)
    {
        if ($this->logger) {
            $recipients = $this->prepareRecipientsForLogging($message->getRecipients());
            $this->logger->error(
                sprintf(
                    'Begin transport of message "%s" to %s via transport "%s"',
                    $message->getSubject(),
                    $recipients,
                    get_class($transport)
                ),
                array(
                    'message' => $message,
                )
            );
        }

        if ($this->eventDispatcher) {
            $this->eventDispatcher->dispatch(
                'avisota_queue_execution_pre_transport',
                new PreTransportMessageEvent($message, $this)
            );
        }
    }

    protected function logSuccessfulStatus(
        TransportInterface $transport,
        MessageInterface $message,
        TransportStatus $status
    ) {
        if ($this->logger) {
            $recipients = $this->prepareRecipientsForLogging($message->getRecipients());
            if ($status->getSuccessfullySend() > 0 && count($status->getFailedRecipients()) > 0) {
                $failedRecipients = $this->prepareRecipientsForLogging($status->getFailedRecipients());
                $this->logger->warning(
                    sprintf(
                        'Transport message "%s" to %s via transport "%s" partial succeeded, failed for: %s with %s',
                        $message->getSubject(),
                        $recipients,
                        get_class($transport),
                        $failedRecipients,
                        $status->getException() ? $status
                            ->getException()
                            ->getMessage() : 'no message'
                    ),
                    array(
                        'message'   => $message,
                        'status'    => $status,
                        'exception' => $status->getException(),
                    )
                );
            } else {
                if (count($status->getFailedRecipients()) > 0) {
                    $failedRecipients = $this->prepareRecipientsForLogging($status->getFailedRecipients());
                    $this->logger->error(
                        sprintf(
                            'Transport message "%s" to %s via transport "%s" failed for: %s with %s',
                            $message->getSubject(),
                            $recipients,
                            get_class($transport),
                            $failedRecipients,
                            $status->getException() ? $status
                                ->getException()
                                ->getMessage() : 'no message'
                        ),
                        array(
                            'message'   => $message,
                            'status'    => $status,
                            'exception' => $status->getException(),
                        )
                    );
                } else {
                    $this->logger->debug(
                        sprintf(
                            'Transport message "%s" to %s via transport "%s" succeed',
                            $message->getSubject(),
                            $recipients,
                            get_class($transport)
                        ),
                        array(
                            'message' => $message,
                            'status'  => $status,
                        )
                    );
                }
            }
        }

        if ($this->eventDispatcher) {
            $this->eventDispatcher->dispatch(
                'avisota_queue_execution_post_transport',
                new PostTransportMessageEvent($message, $this, $status)
            );
        }
    }

    protected function logFailedStatus(
        TransportInterface $transport,
        MessageInterface $message,
        TransportStatus $status
    ) {
        if ($this->logger) {
            $recipients = $this->prepareRecipientsForLogging($message->getRecipients());
            $this->logger->error(
                sprintf(
                    'Could not transport message "%s" to %s via transport "%s": %s',
                    $message->getSubject(),
                    $recipients,
                    get_class($transport),
                    $status
                        ->getException()
                        ->getMessage()
                ),
                array(
                    'message' => $message,
                )
            );
        }

        if ($this->eventDispatcher) {
            $this->eventDispatcher->dispatch(
                'avisota_queue_execution_post_transport',
                new PostTransportMessageEvent($message, $this, $status)
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function enqueue(MessageInterface $message, \DateTime $deliveryDate = null)
    {
        $affectedRows = $this->connection->insert(
            $this->tableName,
            array(
                'enqueue'       => date('Y-m-d H:i:s'),
                'message'       => serialize($message),
                'delivery_date' => $deliveryDate
            )
        );

        return (bool) $affectedRows;
    }

    /**
     * {@inheritdoc}
     */
    public function dequeue(MessageInterface $message)
    {
        $affectedRows = $this->connection->delete(
            $this->tableName,
            array(
                'message' => serialize($message),
            )
        );

        return (bool) $affectedRows;
    }
}
