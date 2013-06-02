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

namespace Avisota\Queue;

use Avisota\Message\MessageInterface;

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
	 * Execute a queue and send all messages.
	 *
	 * @param QueueInterface     $queue
	 * @param TransportInterface $transport
	 */
	public function execute(TransportInterface $transport, QueueExecutionConfig $config = null);

	/**
	 * Enqueue a message.
	 *
	 * @param MessageInterface $message The message to enqueue.
	 * @param \DateTime $deliveryDate The message will not delivered until this date is reached.
	 */
	public function enqueue(MessageInterface $message, \DateTime $deliveryDate = null);
}
