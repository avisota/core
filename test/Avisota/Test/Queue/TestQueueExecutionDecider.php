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

namespace Avisota\Test\Queue;

use Avisota\Message\MessageInterface;
use Avisota\Queue\ExecutionDeciderInterface;

class TestQueueExecutionDecider implements ExecutionDeciderInterface
{
	protected $hits = 0;

	protected $accept;

	function __construct($accept)
	{
		$this->accept = (bool) $accept;
	}

	/**
	 * @return mixed
	 */
	public function getHits()
	{
		return $this->hits;
	}

	/**
	 * Check if the message is accepted.
	 *
	 * @param MessageInterface $message
	 *
	 * @return bool
	 */
	public function accept(MessageInterface $message)
	{
		$this->hits ++;
		return $this->accept;
	}
}
