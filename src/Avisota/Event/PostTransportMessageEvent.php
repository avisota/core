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

namespace Avisota\Event;

use Avisota\Message\MessageInterface;
use Avisota\Queue\EventEmittingQueueInterface;
use Avisota\Transport\TransportStatus;


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

	/**
	 * PostTransportMessageEvent constructor.
	 *
	 * @param MessageInterface            $message
	 * @param EventEmittingQueueInterface $queue
	 * @param TransportStatus             $status
     */
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
