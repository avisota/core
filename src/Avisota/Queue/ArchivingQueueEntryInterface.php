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

interface ArchivingQueueEntryInterface extends QueueEntryInterface
{
	/**
	 * Mark message as successfully transported.
	 */
	public function markSuccess();

	/**
	 * Mark message as faulty.
	 */
	public function markFail();

	/**
	 * Should only be called, to dequeue an entry from the queue, that is not transported.
	 * Use ArchivingQueueEntryInterface::markSuccess or ArchivingQueueEntryInterface::markFail for transported messages.
	 */
	public function dequeue();
}
