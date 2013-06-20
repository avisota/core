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

use Avisota\Message\MessageInterface;

interface MutableQueueInterface extends QueueInterface
{
	/**
	 * Dequeue a message.
	 *
	 * @param MessageInterface $message The message to dequeue.
	 *
	 * @return bool
	 */
	public function dequeue(MessageInterface $message);
}
