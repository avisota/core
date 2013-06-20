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

namespace Avisota\Transport;

use Avisota\Message\MessageInterface;

/**
 * Class TransportStatus
 *
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
class TransportStatus
{
	/**
	 * @var MessageInterface
	 */
	protected $message;

	/**
	 * @var int
	 */
	protected $successfullySended;

	/**
	 * @var array
	 */
	protected $failedRecipients;

	/**
	 * @var \Exception
	 */
	protected $exception;

	function __construct(MessageInterface $message, $successfullySended, $failedRecipients, \Exception $exception = null)
	{
		$this->message = $message;
		$this->successfullySended = (int) $successfullySended;
		$this->failedRecipients   = (array) $failedRecipients;
		$this->exception = $exception;
	}

	/**
	 * @return \Avisota\Message\MessageInterface
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * @return int
	 */
	public function getSuccessfullySended()
	{
		return $this->successfullySended;
	}

	/**
	 * @return array
	 */
	public function getFailedRecipients()
	{
		return $this->failedRecipients;
	}

	/**
	 * @return \Exception
	 */
	public function getException()
	{
		return $this->exception;
	}
}
