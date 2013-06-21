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
 * Event triggered after an
 * {@link http://avisota.github.io/core/class-Avisota.Queue.EventEmittingQueueInterface.html event emitting queue}
 * send a message.
 *
 * @package avisota-core
 */
class PostTransportMessageEvent extends AbstractTransportMessageEvent
{
	/**
	 * The transport status.
	 *
	 * @var bool
	 */
	protected $succeeded;

	/**
	 * The transport exception.
	 *
	 * @var \Exception|null
	 */
	protected $exception;

	function __construct(MessageInterface $message, EventEmittingQueueInterface $queue, $succeeded, \Exception $exception = null)
	{
		parent::__construct($message, $queue);
		$this->succeeded = $succeeded;
		$this->exception = $exception;
	}

	/**
	 * Check if the transport succeeded.
	 *
	 * @return boolean
	 */
	public function isSucceeded()
	{
		return $this->succeeded;
	}

	/**
	 * If available, return the transport exception.
	 *
	 * @return \Exception|null
	 */
	public function getException()
	{
		return $this->exception;
	}
}