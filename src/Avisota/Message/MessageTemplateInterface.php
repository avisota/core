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

/**
 * Class MessageTemplateInterface
 *
 * @package avisota-core
 */
interface MessageTemplateInterface
{
	/**
	 * Create a message for the given recipient.
	 *
	 * @param Recipient $recipient
	 *
	 * @return \Swift_Message
	 */
	public function createMessage(Recipient $recipient);
}
