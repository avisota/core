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

namespace Avisota\Test\Message;

use Avisota\Message\MessageInterface;

class TestMessage implements MessageInterface
{
	protected $text;

	function __construct()
	{
		$this->text = "This is a unit test message.\r\n";
		$this->text .= "timestamp: " . date('Y-m-d H:i:s') . "\r\n";
		$this->text .= "random content: " . mt_rand() . "\r\n";
	}

	/**
	 * @return string
	 */
	public function getText()
	{
		return $this->text;
	}

	/**
	 * @return array
	 */
	public function getRecipient()
	{
		return array('unittest@avisota.org');
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRecipientDetails()
	{
		return array(
			'firstname' => 'Unit',
			'surname'   => 'Text',
			'email'     => 'unittest@avisota.org',
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getCopyRecipient()
	{
		return array();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlindCopyRecipient()
	{
		return array();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFrom()
	{
		return 'unittest@avisota.org';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSender()
	{
		return '';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getReplyTo()
	{
		return '';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSubject()
	{
		return 'Unit Test test message';
	}

	/**
	 * {@inheritdoc}
	 */
	public function createSwiftMessage()
	{
		$message = \Swift_Message::newInstance();
		$message->setTo($this->getRecipient());
		$message->setFrom($this->getFrom());
		$message->setSubject($this->getSubject());
		$message->setBody($this->text, 'text/plain');
		return $message;
	}

	/**
	 * {@inheritdoc}
	 */
	public function serialize()
	{
		return $this->text;
	}

	/**
	 * {@inheritdoc}
	 */
	public function unserialize($serialized)
	{
		$this->text = $serialized;
	}
}
