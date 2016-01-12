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

namespace Avisota\Transport;

use Avisota\Message\MessageInterface;
use Avisota\Renderer\MessageRendererInterface;

/**
 * Transport using a pre configured swift transport.
 *
 * @package avisota-core
 */
class SwiftTransport extends AbstractTransport
{
    /**
     * @var \Swift_Mailer|null
     */
    protected $swiftMailer;

    public function __construct(\Swift_Mailer $swiftMailer, MessageRendererInterface $renderer)
    {
        $this->setSwiftMailer($swiftMailer);
        $this->setRenderer($renderer);
    }

    /**
     * @param \Swift_Mailer $swiftMailer
     */
    public function setSwiftMailer(\Swift_Mailer $swiftMailer)
    {
        $this->swiftMailer = $swiftMailer;
        return $this;
    }

    /**
     * @return \Swift_Mailer
     */
    public function getSwiftMailer()
    {
        return $this->swiftMailer;
    }

    /**
     * {@inheritdoc}
     */
    public function initialise()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function send(MessageInterface $message)
    {
        $email = $this->renderer->renderMessage($message);

        $failedRecipients      = array();
        $successfullySendCount = $this->swiftMailer->send($email, $failedRecipients);

        return new TransportStatus($message, $successfullySendCount, $failedRecipients);
    }
}
