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

use Avisota\Message\MessageTemplateInterface;
use Avisota\RecipientSource\RecipientSourceInterface;
use Avisota\Transport\TransportInterface;

class QueueManager
{
	/**
	 * Execute a queue and send all messages.
	 *
	 * @param QueueInterface     $queue
	 * @param TransportInterface $transport
	 */
	public function execute(QueueInterface $queue, TransportInterface $transport)
	{

	}

	/**
	 * Partial execute a queue until a given number of messages are reached.
	 *
	 * @param int                $count
	 * @param QueueInterface     $queue
	 * @param TransportInterface $transport
	 */
	public function executePartial($count, QueueInterface $queue, TransportInterface $transport)
	{

	}

	/**
	 * Enqueue the message for all recipients into the queue.
	 *
	 * @param MessageTemplateInterface $message
	 * @param RecipientSourceInterface $recipientSource
	 * @param QueueInterface           $queue
	 */
	public function enqueue(MessageTemplateInterface $message, RecipientSourceInterface $recipientSource, QueueInterface $queue)
	{

	}

	/**
	 * Enqueue the message into the queue.
	 *
	 * @param \Swift_Message $message
	 * @param QueueInterface $queue
	 */
	public function enqueueMessage(\Swift_Message $message, QueueInterface $queue)
	{

	}
}
