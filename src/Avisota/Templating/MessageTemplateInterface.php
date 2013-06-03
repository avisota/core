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

namespace Avisota\Templating;

use Avisota\Recipient\RecipientInterface;

interface MessageTemplateInterface
{
	/**
	 * Render a message for the given recipient.
	 *
	 * @param RecipientInterface $recipientEmail The recipient data.
	 * @param array              $newsletterData Additional newsletter data.
	 *
	 * @return \Avisota\Message\MessageInterface
	 */
	public function render(RecipientInterface $recipient, array $newsletterData = array());
}
