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

use Avisota\Message\MessageInterface;
use Avisota\Transport\TransportInterface;
use Avisota\Transport\TransportStatus;

/**
 * The basic message queue interface.
 *
 * @package avisota-core
 */
interface QueueInterface
{
	/**
	 * Check if the queue is empty.
	 *
	 * @return bool
	 */
	public function isEmpty();

	/**
	 * Return the length of the queue.
	 *
	 * @return int
	 */
	public function length();

	/**
	 * Return all messages from the queue.
	 *
	 * @return MessageInterface[]
	 */
	public function getMessages();

	/**
	 * Execute a queue and send all messages.
	 *
	 * @param QueueInterface     $queue
	 * @param TransportInterface $transport
	 *
	 * @return TransportStatus[]
	 */
	public function execute(TransportInterface $transport, ExecutionConfig $config = null);

	/**
	 * Enqueue a message.
	 *
	 * @param MessageInterface $message The message to enqueue.
	 * @param \DateTime $deliveryDate The message will not delivered until this date is reached.
	 *
	 * @return bool
	 */
	public function enqueue(MessageInterface $message, \DateTime $deliveryDate = null);
}
