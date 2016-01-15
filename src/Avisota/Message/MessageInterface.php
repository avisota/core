<?php

/**
 * Avisota newsletter and mailing system
 *
 * PHP Version 5.3
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
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
	public function getRecipients();

	/**
	 * @return array
	 */
	public function getCopyRecipients();

	/**
	 * @return array
	 */
	public function getBlindCopyRecipients();

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
}
