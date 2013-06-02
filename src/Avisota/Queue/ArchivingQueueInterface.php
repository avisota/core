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

interface ArchivingQueueInterface extends QueueInterface
{
	/**
	 * Clean transported message information.
	 *
	 * @return bool
	 */
	public function cleanup();

	/**
	 * Return the count of send messages.
	 *
	 * @return int
	 */
	public function sendCount();

	/**
	 * Return the successfully send messages.
	 *
	 * @return ArchivingQueueEntryInterface
	 */
	public function successfullyMessages();

	/**
	 * Return the faulty send messages.
	 *
	 * @return int
	 */
	public function faultyMessages();
}
