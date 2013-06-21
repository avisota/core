<?php

/**
 * Avisota newsletter and mailing system
 *
 * PHP Version 5.3
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
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
class SimpleDatabaseQueue implements MutableQueueInterface, EventEmittingQueueInterface, LoggingQueueInterface
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
	function __construct(
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
			}
			else {
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
		$statement = $query = $queryBuilder
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
		$queryBuilder = $this->connection->createQueryBuilder();
		$queryBuilder
			->select('q.*')
			->from($this->tableName, 'q');

		$decider = null;

		if ($config) {
			$decider = $config->getDecider();
			if ($config->getLimit() > 0) {
				$queryBuilder->setMaxResults($config->getLimit());
			}
		}

		/** @var Statement $statement */
		$statement = $queryBuilder->execute();
		$resultSet = $statement->fetchAll();

		$results = array();

		/**
		 * Initialise transport system
		 */
		$transport->initialise();

		foreach ($resultSet as $record) {
			if ($record['delivery_date']) {
				$deliveryDate = \DateTime::createFromFormat('Y-m-d H:i:s', $record['delivery_date']);
				if ($deliveryDate->getTimestamp() > time()) {
					continue;
				}
			}

			try {
				/** @var MessageInterface $message */
				$message = unserialize($record['message']);
			}
			catch (\Exception $e) {
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

				continue;
			}

			// skip message
			if ($decider && !$decider->accept($message)) {
				continue;
			}

			if ($this->eventDispatcher) {
				$this->eventDispatcher->dispatch(
					'avisota_queue_execution_pre_transport',
					new PreTransportMessageEvent($message, $this)
				);
			}

			try {
				// try to transport message
				$status = $transport->transport($message);

				// log successful transport
				if ($this->logger) {
					$recipients = array();
					foreach ($message->getRecipient() as $email => $name) {
						if (is_numeric($email)) {
							$recipients[] = $name;
						}
						else {
							$recipients[] = $email;
						}
					}
					if ($status->getSuccessfullySend() > 0 && count($status->getFailedRecipients()) > 0) {
						$failedRecipients = array();
						foreach ($status->getFailedRecipients() as $email => $name) {
							if (is_numeric($email)) {
								$failedRecipients[] = $name;
							}
							else {
								$failedRecipients[] = sprintf('%s <%s>', $email, $name);
							}
						}
						$this->logger->warning(
							sprintf(
								'Transport message "%s" to %s via transport "%s" partial succeeded, failed for: %s with %s',
								$message->getSubject(),
								implode(', ', $recipients),
								get_class($transport),
								implode(', ', $failedRecipients),
								$status->getException() ? $status->getException()->getMessage() : 'no message'
							),
							array(
								 'message' => $message,
								 'status'  => $status,
								 'exception' => $status->getException(),
							)
						);
					}
					else if (count($status->getFailedRecipients()) > 0) {
						$failedRecipients = array();
						foreach ($status->getFailedRecipients() as $email => $name) {
							if (is_numeric($email)) {
								$failedRecipients[] = $name;
							}
							else {
								$failedRecipients[] = sprintf('%s <%s>', $email, $name);
							}
						}
						$this->logger->error(
							sprintf(
								'Transport message "%s" to %s via transport "%s" failed for: %s with %s',
								$message->getSubject(),
								implode(', ', $recipients),
								get_class($transport),
								implode(', ', $failedRecipients),
								$status->getException() ? $status->getException()->getMessage() : 'no message'
							),
							array(
								 'message' => $message,
								 'status'  => $status,
								 'exception' => $status->getException(),
							)
						);
					}
					else {
						$this->logger->debug(
							sprintf(
								'Transport message "%s" to %s via transport "%s" succeed',
								$message->getSubject(),
								implode(', ', $recipients),
								get_class($transport)
							),
							array(
								 'message' => $message,
								 'status'  => $status,
							)
						);
					}
				}

				if ($this->eventDispatcher) {
					$this->eventDispatcher->dispatch(
						'avisota_queue_execution_post_transport',
						new PostTransportMessageEvent($message, $this, true)
					);
				}
			}
			catch (\Exception $e) {
				$status = new TransportStatus($message, 0, $message->getRecipient(), $e);

				// log failed transport
				if ($this->logger) {
					$recipients = array();
					foreach ($message->getRecipient() as $email => $name) {
						if (is_numeric($email)) {
							$recipients[] = $name;
						}
						else {
							$recipients[] = $email;
						}
					}
					$this->logger->error(
						sprintf(
							'Could not transport message "%s" to %s via transport "%s": %s',
							$message->getSubject(),
							implode(', ', $recipients),
							get_class($transport),
							$e->getMessage()
						),
						array(
							 'message' => $message,
						)
					);
				}

				if ($this->eventDispatcher) {
					$this->eventDispatcher->dispatch(
						'avisota_queue_execution_post_transport',
						new MessagePostTransportEvent($message, $this, false, $e)
					);
				}
			}

			$results[] = $status;

			$this->connection->delete($this->tableName, array('id' => $record['id']));
		}

		/**
		 * Flush transport
		 */
		$transport->flush();
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