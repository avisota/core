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

interface MessageTemplateInterface
{
	/**
	 * @param string|array $recipientEmail The recipient email address or an array of recipient details.
	 * @param array        $newsletterData
	 *
	 * @return \Avisota\Message\MessageInterface
	 */
	public function render($recipient, array $newsletterData = array());
}
