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

use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * A queue that emits events over an event dispatcher.
 *
 * @package avisota-core
 */
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
