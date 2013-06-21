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
 * Abstract transport using a swift transport.
 *
 * @package avisota-core
 */
abstract class SwiftTransport implements TransportInterface
{
	/**
	 * @var \Swift_Mailer|null
	 */
	protected $swiftMailer;

	/**
	 * @return \Swift_Mailer
	 */
	abstract protected function createMailer();

	protected function resetMailer()
	{
		$this->swiftMailer = null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function initialise()
	{
		if (!$this->swiftMailer) {
			$this->swiftMailer = $this->createMailer();
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function flush()
	{
	}

	/**
	 * {@inheritdoc}
	 */
	public function transport(MessageInterface $message)
	{
		$email = $message->createSwiftMessage();

		$failedRecipients = array();
		$successfullySendCount = $this->swiftMailer->send($email, $failedRecipients);

		return new TransportStatus($message, $successfullySendCount, $failedRecipients);
	}
}
