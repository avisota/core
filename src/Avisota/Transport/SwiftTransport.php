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

    /**
     * SwiftTransport constructor.
     *
     * @param \Swift_Mailer            $swiftMailer
     * @param MessageRendererInterface $renderer
     */
    public function __construct(\Swift_Mailer $swiftMailer, MessageRendererInterface $renderer)
    {
        $this->setSwiftMailer($swiftMailer);
        $this->setRenderer($renderer);
    }

    /**
     * @param \Swift_Mailer $swiftMailer
     *
     * @return $this
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
     * Initialise transport.
     *
     * @return void
     */
    public function initialise()
    {
    }

    /**
     * Flush transport.
     *
     * @return void
     */
    public function flush()
    {
    }

    /**
     * Transport a message.
     *
     * @param MessageInterface $message
     *
     * @return TransportStatus
     */
    public function send(MessageInterface $message)
    {
        $email = $this->renderer->renderMessage($message);

        $failedRecipients      = array();
        $successfullySendCount = $this->swiftMailer->send($email, $failedRecipients);

        return new TransportStatus($message, $successfullySendCount, $failedRecipients);
    }
}
