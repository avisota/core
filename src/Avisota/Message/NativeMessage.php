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
	public function getRecipient()
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
	public function getCopyRecipient()
	{
		return $this->message->getCc();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBlindCopyRecipient()
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
	 * {@inheritdoc}
	 */
	public function createSwiftMessage()
	{
		return $this->message;
	}

	/**
	 * {@inheritdoc}
	 */
	public function serialize()
	{
		return serialize($this->message);

		/*
		$data = new \stdClass();

		$data->subject       = $message->getSubject();
		$data->date          = $message->getDate();
		$data->returnPath    = $message->getReturnPath();
		$data->sender        = $message->getSender();
		$data->from          = $message->getFrom();
		$data->replyTo       = $message->getReplyTo();
		$data->to            = $message->getTo();
		$data->cc            = $message->getCc();
		$data->bcc           = $message->getBcc();
		$data->priority      = $message->getPriority();
		$data->readReceiptTo = $message->getReadReceiptTo();

		$data->body = $this->serializeChildren($message);

		return $data;
		*/
	}

	/*
	public function serializeChildren(\Swift_Mime_MimeEntity $entry)
	{
		$data = new \stdClass();

		$data->contentType = $entry->getContentType();
		$data->id          = $entry->getId();
		$entry->getHeaders();
		$data->body          = $entry->getBody();
		$data->description   = $entry->getDescription();
		$data->maxLineLength = $entry->getMaxLineLength();
		$data->boundary      = $entry->getBoundary();

		if ($entry instanceof \Swift_Attachment) {
			/** @var \Swift_Attachment $entry * /
			var_dump($entry->getBody());
		}

		if ($entry instanceof \Swift_Mime_MimePart) {
			/** @var \Swift_Mime_MimePart $entry * /
			$data->charset = $entry->getCharset();
			$data->format  = $entry->getFormat();
			$data->delSp   = $entry->getDelSp();
		}

		$data->headers = array();
		$headers = $entry->getHeaders();
		foreach ($headers->listAll() as $header) {
			$data->headers[$header] = array();

			/** @var \Swift_Mime_Header[] $contents * /
			$contents = $headers->getAll($header);
			foreach ($contents as $content) {
				$data->headers[$header] = $content->getFieldBody();
			}
		}

		$data->children = array();
		foreach ($entry->getChildren() as $children) {
			$data->children[] = $this->serializeChildren($children);
		}

		return $data;
	}
	*/

	/**
	 * {@inheritdoc}
	 */
	public function unserialize($serialized)
	{
		$this->message = unserialize($serialized);
	}
}
