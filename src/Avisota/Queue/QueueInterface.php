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
	 * Return the next message.
	 *
	 * @return QueueEntryInterface
	 */
	public function next();

	/**
	 * Enqueue a message.
	 *
	 * @param \Swift_Message $message The message to enqueue.
	 * @param \DateTime $deliveryDate The message will not delivered until this date is reached.
	 */
	public function enqueue(\Swift_Message $message, \DateTime $deliveryDate = null);
}
