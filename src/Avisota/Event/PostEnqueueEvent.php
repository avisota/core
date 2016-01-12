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
use Avisota\Queue\QueueInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Abstract event triggered by an
 * {@link http://avisota.github.io/core/class-Avisota.Queue.EventEmittingQueueInterface.html
 * event emitting queue}.
 *
 * @package avisota-core
 */
class PostEnqueueEvent extends Event
{
	const NAME = 'avisota.queue.post-enqueue';

	/**
	 * The message to be transported.
	 *
	 * @var MessageInterface
	 */
	protected $message;

	/**
	 * The queue that send the message.
	 *
	 * @var QueueInterface
	 */
	protected $queue;

	/**
	 * PostEnqueueEvent constructor.
	 *
	 * @param MessageInterface $message
	 * @param QueueInterface   $queue
     */
    public function __construct(MessageInterface $message, QueueInterface $queue)
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
	 * @return QueueInterface
	 */
	public function getQueue()
	{
		return $this->queue;
	}
}
