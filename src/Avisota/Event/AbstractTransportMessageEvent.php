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
 * Abstract event triggered by an
 * {@link http://avisota.github.io/core/class-Avisota.Event.EventEmittingQueueInterface.html event emitting queue}.
 *
 * @package avisota-core
 */
abstract class AbstractTransportMessageEvent extends Event
{
	/**
	 * The message to be transported.
	 *
	 * @var MessageInterface
	 */
	protected $message;

	/**
	 * The queue that send the message.
	 *
	 * @var EventEmittingQueueInterface
	 */
	protected $queue;

	function __construct(MessageInterface $message, EventEmittingQueueInterface $queue)
	{
		$this->message = $message;
		$this->queue   = $queue;
	}

	/**
	 * Get the message to be transported.
	 *
	 * @return \Avisota\Message\MessageInterface
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * Get the transporting queue.
	 *
	 * @return \Avisota\Queue\EventEmittingQueueInterface
	 */
	public function getQueue()
	{
		return $this->queue;
	}
}