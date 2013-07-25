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

namespace Avisota\Transport;

use Avisota\Message\MessageInterface;
use Avisota\Queue\QueueInterface;

/**
 * Queuing transport.
 *
 * @package avisota-core
 */
class QueueTransport implements TransportInterface
{
	/**
	 * @var QueueInterface
	 */
	protected $queue;

	function __construct(QueueInterface $queue)
	{
		$this->queue = $queue;
	}

	/**
	 * @param QueueInterface $queue
	 */
	public function setQueue(QueueInterface $queue)
	{
		$this->queue = $queue;
		return $this;
	}

	/**
	 * @return \Swift_Mailer
	 */
	public function getQueue()
	{
		return $this->queue;
	}

	/**
	 * {@inheritdoc}
	 */
	public function initialise()
	{
	}

	/**
	 * {@inheritdoc}
	 */
	public function flush()
	{
	}

	/**
	 * {@inheritdoc}
	 */
	public function send(MessageInterface $message)
	{
		$this->queue->enqueue($message);
		return new TransportStatus($message, 0, array());
	}
}
