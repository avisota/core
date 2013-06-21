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

use Avisota\Message\MessageTemplateInterface;
use Avisota\RecipientSource\RecipientSourceInterface;

/**
 * A collection of helper functions.
 *
 * @package avisota-core
 */
class QueueHelper
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
		throw new Exception('Not implemented yet');
	}
}
