<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota-core
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Test;

use Avisota\Queue\ExecutionConfig;
use Avisota\Test\Imap\ImapMailboxChecker;
use Avisota\Test\Message\TestMessage;
use Avisota\Test\Queue\TestQueueExecutionDecider;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Statement;

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

		$count    = mt_rand(10, 100);
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

		$count    = mt_rand(10, 100);
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
}
