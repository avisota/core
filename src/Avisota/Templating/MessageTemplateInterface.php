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

namespace Avisota\Templating;

use Avisota\Recipient\RecipientInterface;

/**
 * A template to generate a message from.
 *
 * @package avisota-core
 */
interface MessageTemplateInterface
{
    /**
     * Render a message for the given recipient.
     *
     * @param RecipientInterface $recipientEmail The main recipient.
     * @param array              $newsletterData Additional newsletter data.
     *
     * @return \Avisota\Message\MessageInterface
     */
    public function render(RecipientInterface $recipient = null, array $newsletterData = array());
}
