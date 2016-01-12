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

namespace Avisota\Test;

use Avisota\Event\PostTransportMessageEvent;
use Avisota\Event\PreTransportMessageEvent;
use Avisota\Queue\EventEmittingQueueInterface;
use Avisota\Queue\ExecutionConfig;
use Avisota\Queue\LoggingQueueInterface;
use Avisota\Test\Imap\ImapMailboxChecker;
use Avisota\Test\Message\TestMessage;
use Avisota\Test\Queue\TestQueueExecutionDecider;
use Avisota\Test\Transport\NoOpTransport;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Statement;
use Monolog\Handler\TestHandler;
use Monolog\Logger;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcher;

abstract class AbstractQueueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TransportProviderInterface
     */
    protected $transportProvider = null;

    /**
     * @var
     */
    protected $imapConnectionProvider = null;

    abstract protected function createQueue();

    /**
     * @param ressource $imapConnection
     * @param array     $messages
     */
    protected function checkMessagesInMailbox($imapConnection, array $messages)
    {
        $imapMailboxChecker = new ImapMailboxChecker();
        return $imapMailboxChecker->checkMessages($imapConnection, $messages);
    }

    public function testSingleEnqueue()
    {
        $queue = $this->createQueue();

        $message = new TestMessage();
        $queue->enqueue($message);

        $this->assertEquals(1, $queue->length());
        $this->assertEquals(array($message), $queue->getMessages());
    }

    public function testMultipleEnqueue()
    {
        $queue = $this->createQueue();

        $count    = mt_rand(5, 10);
        $messages = array();

        for ($i = 0; $i < $count; $i++) {
            $message    = new TestMessage();
            $messages[] = $message;
            $queue->enqueue($message);
        }

        $this->assertEquals(
            $count,
            $queue->length()
        );
        $this->assertEquals(
            $messages,
            $queue->getMessages()
        );
    }

    public function testSingleTransport()
    {
        $queue          = $this->createQueue();
        $transport      = $this->transportProvider->createTransport();
        $imapConnection = $this->imapConnectionProvider->createImapConnection();

        if (!$transport) {
            $this->markTestSkipped('Transport unavailable!');
            return;
        }

        $message = new TestMessage();
        $queue->enqueue($message);

        $queue->execute($transport);

        $this->assertEquals(0, $queue->length());

        if (!$imapConnection) {
            $this->markTestIncomplete('Cannot check send messages, because imap connection is not available.');
            return;
        }

        $this->assertEquals(1, $this->checkMessagesInMailbox($imapConnection, array($message)));
    }

    public function testMultipleTransport()
    {
        $queue          = $this->createQueue();
        $transport      = $this->transportProvider->createTransport();
        $imapConnection = $this->imapConnectionProvider->createImapConnection();

        if (!$transport) {
            $this->markTestSkipped('Transport unavailable!');
            return;
        }

        $count    = mt_rand(5, 10);
        $messages = array();

        for ($i = 0; $i < $count; $i++) {
            $message    = new TestMessage();
            $messages[] = $message;
            $queue->enqueue($message);
        }

        $queue->execute($transport);

        $this->assertEquals(0, $queue->length());

        if (!$imapConnection) {
            $this->markTestIncomplete('Cannot check send messages, because imap connection is not available.');
            return;
        }

        $this->assertEquals($count, $this->checkMessagesInMailbox($imapConnection, $messages));
    }

    public function testSingleTransportDeciderDeny()
    {
        $queue          = $this->createQueue();
        $transport      = $this->transportProvider->createTransport();
        $imapConnection = $this->imapConnectionProvider->createImapConnection();

        if (!$transport) {
            $this->markTestSkipped('Transport unavailable!');
            return;
        }

        $message = new TestMessage();
        $queue->enqueue($message);

        $decider = new TestQueueExecutionDecider(false);
        $config  = new ExecutionConfig();
        $config->setDecider($decider);

        $queue->execute($transport, $config);

        $this->assertEquals(
            1,
            $queue->length()
        );
        $this->assertEquals(
            1,
            $decider->getHits()
        );

        if (!$imapConnection) {
            $this->markTestIncomplete('Cannot check send messages, because imap connection is not available.');
            return;
        }

        $this->assertEquals(0, $this->checkMessagesInMailbox($imapConnection, array($message)));
    }

    public function testSingleTransportDeciderAllow()
    {
        $queue          = $this->createQueue();
        $transport      = $this->transportProvider->createTransport();
        $imapConnection = $this->imapConnectionProvider->createImapConnection();

        if (!$transport) {
            $this->markTestSkipped('Transport unavailable!');
            return;
        }

        $message = new TestMessage();
        $queue->enqueue($message);

        $decider = new TestQueueExecutionDecider(true);
        $config  = new ExecutionConfig();
        $config->setDecider($decider);

        $queue->execute($transport, $config);

        $this->assertEquals(
            0,
            $queue->length()
        );
        $this->assertEquals(
            1,
            $decider->getHits()
        );

        if (!$imapConnection) {
            $this->markTestIncomplete('Cannot check send messages, because imap connection is not available.');
            return;
        }

        $this->assertEquals(1, $this->checkMessagesInMailbox($imapConnection, array($message)));
    }

    public function testLoggingSucceed()
    {
        $queue = $this->createQueue();

        if (!$queue instanceof LoggingQueueInterface) {
            $this->markTestSkipped('Queue of type ' . get_class($queue) . ' does not log actions.');
            return;
        }

        $transport = new NoOpTransport(NoOpTransport::SUCCEED);

        $handler = new TestHandler();
        $logger  = new Logger('test', array($handler));
        $queue->setLogger($logger);

        $message = new TestMessage();
        $queue->enqueue($message);
        $queue->execute($transport);

        $records = $handler->getRecords();

        // the logger has to be at least one record
        $this->assertTrue(count($records) > 0);

        // the logger has to be at least one debug record (=> successful transport)
        $this->assertTrue(
            array_reduce(
                $records,
                function (&$result, $record) {
                    return $result || $record['level'] == Logger::DEBUG;
                },
                false
            )
        );
    }

    public function testLoggingSucceedPartial()
    {
        $queue = $this->createQueue();

        if (!$queue instanceof LoggingQueueInterface) {
            $this->markTestSkipped('Queue of type ' . get_class($queue) . ' does not log actions.');
            return;
        }

        $transport = new NoOpTransport(NoOpTransport::SUCCEED_PARTIAL);

        $handler = new TestHandler();
        $logger  = new Logger('test', array($handler));
        $queue->setLogger($logger);

        $message = new TestMessage();
        $queue->enqueue($message);
        $queue->execute($transport);

        $records = $handler->getRecords();

        // the logger has to be at least one record
        $this->assertTrue(count($records) > 0);

        // the logger has to be at least one debug record (=> successful transport)
        $this->assertTrue(
            array_reduce(
                $records,
                function (&$result, $record) {
                    return $result || $record['level'] == Logger::WARNING;
                },
                false
            )
        );
    }

    public function testLoggingFailed()
    {
        $queue = $this->createQueue();

        if (!$queue instanceof LoggingQueueInterface) {
            $this->markTestSkipped('Queue of type ' . get_class($queue) . ' does not log actions.');
            return;
        }

        $transport = new NoOpTransport(NoOpTransport::FAILED);

        $handler = new TestHandler();
        $logger  = new Logger('test', array($handler));
        $queue->setLogger($logger);

        $message = new TestMessage();
        $queue->enqueue($message);
        $queue->execute($transport);

        $records = $handler->getRecords();

        // the logger has to be at least one record
        $this->assertTrue(count($records) > 0);

        // the logger has to be at least one debug record (=> successful transport)
        $this->assertTrue(
            array_reduce(
                $records,
                function (&$result, $record) {
                    return $result || $record['level'] == Logger::ERROR;
                },
                false
            )
        );
    }

    public function testEventDispatcher()
    {
        $queue = $this->createQueue();

        if (!$queue instanceof EventEmittingQueueInterface) {
            $this->markTestSkipped('Queue of type ' . get_class($queue) . ' does not emit events.');
            return;
        }

        $transport = new NoOpTransport(NoOpTransport::FAILED);
        $self      = $this;
        $hits      = 0;

        $eventDispatcher = new EventDispatcher();
        $eventDispatcher->addListener(
            'avisota_queue_execution_pre_transport',
            function (Event $event) use ($self, &$hits) {
                $self->assertTrue($event instanceof PreTransportMessageEvent);
                $hits++;
            }
        );
        $eventDispatcher->addListener(
            'avisota_queue_execution_post_transport',
            function (Event $event) use ($self, &$hits) {
                $self->assertTrue($event instanceof PostTransportMessageEvent);
                $hits++;
            }
        );
        $queue->setEventDispatcher($eventDispatcher);

        $message = new TestMessage();
        $queue->enqueue($message);
        $queue->execute($transport);

        $this->assertEquals(2, $hits);
    }
}
