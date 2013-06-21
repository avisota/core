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

namespace Avisota\Event;

use Avisota\Message\MessageInterface;
use Avisota\Queue\EventEmittingQueueInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Event triggered before an
 * {@link http://avisota.github.io/core/class-Avisota.Event.EventEmittingQueueInterface.html event emitting queue}
 * send a message.
 *
 * @package avisota-core
 */
class PreTransportMessageEvent extends AbstractTransportMessageEvent
{
}