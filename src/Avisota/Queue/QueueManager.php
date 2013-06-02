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
	 * Enqueue the message for all recipients into the queue.
	 *
	 * @param MessageInterface         $message
	 * @param RecipientSourceInterface $recipientSource
	 * @param QueueInterface           $queue
	 */
	public function enqueue(MessageInterface $message, RecipientSourceInterface $recipientSource, QueueInterface $queue)
	{

	}
}
