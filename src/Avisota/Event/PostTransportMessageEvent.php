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
use Avisota\Transport\TransportStatus;
use Symfony\Component\EventDispatcher\Event;

/**
 * Event triggered after an
 * {@link http://avisota.github.io/core/class-Avisota.Queue.EventEmittingQueueInterface.html
 * event emitting queue}
 * send a message.
 *
 * @package avisota-core
 */
class PostTransportMessageEvent extends AbstractTransportMessageEvent
{
	/**
	 * @var TransportStatus
	 */
	protected $status;

	public function __construct(
		MessageInterface $message,
		EventEmittingQueueInterface $queue,
		TransportStatus $status
	) {
		parent::__construct($message, $queue);
		$this->status = $status;
	}

	/**
	 * @return \Avisota\Transport\TransportStatus
	 */
	public function getStatus()
	{
		return $this->status;
	}
}