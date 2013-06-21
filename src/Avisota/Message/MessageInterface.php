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
 * The basic message interface.
 *
 * @package avisota-core
 */
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