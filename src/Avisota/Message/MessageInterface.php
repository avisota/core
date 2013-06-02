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

namespace Avisota\Message;

interface MessageInterface extends \Serializable
{
	/**
	 * @return array
	 */
	public function getRecipient();

	/**
	 * @return array
	 */
	public function getRecipientDetails();

	/**
	 * @return array
	 */
	public function getCopyRecipient();

	/**
	 * @return array
	 */
	public function getBlindCopyRecipient();

	/**
	 * @return string
	 */
	public function getFrom();

	/**
	 * @return string
	 */
	public function getSender();

	/**
	 * @return string
	 */
	public function getReplyTo();

	/**
	 * @return string
	 */
	public function getSubject();

	/**
	 * @return \Swift_Message
	 */
	public function createSwiftMessage();
}