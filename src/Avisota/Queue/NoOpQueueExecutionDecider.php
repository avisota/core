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

class NoOpQueueExecutionDecider implements QueueExecutionDeciderInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function accept(MessageInterface $message)
	{
		return true;
	}
}