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

namespace Avisota\Message;

/**
 * A message containing a native swift message.
 *
 * @package avisota-core
 */
class NativeMessage implements MessageInterface
{
	/**
	 * The swift message.
	 *
	 * @var \Swift_Message
	 */
	protected $message;

	/**
	 * @param \Swift_Message $message
	 */
	public function __construct(\Swift_Message $message)
	{
		$this->message = $message;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRecipients()
	{
		return $this->message->getTo();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRecipientDetails()
	{
		return array();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getCopyRecipients()
	{
		return $this->message->getCc();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlindCopyRecipients()
	{
		return $this->message->getBcc();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFrom()
	{
		return $this->message->getFrom();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSender()
	{
		return $this->message->getSender();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getReplyTo()
	{
		return $this->message->getReplyTo();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSubject()
	{
		return $this->message->getSubject();
	}

	/**
	 * @return \Swift_Message
	 */
	public function getMessage()
	{
		return $this->message;
	}

	/**
	 * {@inheritdoc}
	 */
	public function serialize()
	{
		return serialize($this->message);
	}

	/**
	 * {@inheritdoc}
	 */
	public function unserialize($serialized)
	{
		$this->message = unserialize($serialized);
	}
}
