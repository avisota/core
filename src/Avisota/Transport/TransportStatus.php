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

namespace Avisota\Transport;

use Avisota\Message\MessageInterface;

/**
 * The transport status information.
 *
 * @package avisota-core
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
	protected $successfullySend;

	/**
	 * @var array
	 */
	protected $failedRecipients;

	/**
	 * @var \Exception
	 */
	protected $exception;

	function __construct(MessageInterface $message, $successfullySend, array $failedRecipients = array(), \Exception $exception = null)
	{
		$this->message = $message;
		$this->successfullySend = (int) $successfullySend;
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
	public function getSuccessfullySend()
	{
		return $this->successfullySend;
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
