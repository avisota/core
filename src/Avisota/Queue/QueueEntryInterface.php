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

interface QueueEntryInterface
{
	/**
	 * Return the message.
	 *
	 * @return \Swift_Message
	 */
	public function getMessage();

	/**
	 * Exclusive reserve (lock) this entry.
	 * Must be called before transport the message.
	 * Return false if the entry is now reserved by another process.
	 *
	 * @return bool
	 */
	public function reserve();

	/**
	 * Free a previously reserved entry.
	 */
	public function free();

	/**
	 * Dequeue this entry from the queue.
	 * Must be called if the entry is successfully transported.
	 */
	public function dequeue();
}
