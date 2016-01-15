<?php

/**
 * Avisota newsletter and mailing system
 *
 * PHP Version 5.3
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
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
