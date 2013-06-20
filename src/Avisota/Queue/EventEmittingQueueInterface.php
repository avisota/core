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

use Symfony\Component\EventDispatcher\EventDispatcher;

interface EventEmittingQueueInterface
{
	/**
	 * Set the event dispatcher for this queue.
	 *
	 * @param EventDispatcher|null $eventDispatcher
	 *
	 * @return QueueInterface
	 */
	public function setEventDispatcher(EventDispatcher $eventDispatcher = null);

	/**
	 * Get the event dispatcher for this queue.
	 *
	 * @return EventDispatcher|null
	 */
	public function getEventDispatcher();
}
