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

namespace Avisota\Event;

use Avisota\Message\MessageInterface;
use Avisota\Queue\EventEmittingQueueInterface;
use Symfony\Component\EventDispatcher\Event;

class MessagePostTransportEvent extends AbstractMessagePreTransportEvent
{
	/**
	 * @var bool
	 */
	protected $succeeded;

	/**
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
	 * @return boolean
	 */
	public function getSucceeded()
	{
		return $this->succeeded;
	}

	/**
	 * @return \Exception|null
	 */
	public function getException()
	{
		return $this->exception;
	}
}