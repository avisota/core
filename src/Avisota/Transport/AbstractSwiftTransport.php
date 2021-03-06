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

/**
 * Abstract transport using a swift transport.
 *
 * @package avisota-core
 */
abstract class AbstractSwiftTransport extends AbstractTransport
{
    /**
     * @var \Swift_Mailer|null
     */
    protected $swiftMailer;

    /**
     * @return \Swift_Mailer
     */
    abstract protected function createMailer();

    /**
     * @return void
     */
    protected function resetMailer()
    {
        $this->swiftMailer = null;
    }

    /**
     * Initialise transport.
     *
     * @return void
     */
    public function initialise()
    {
        if (!$this->swiftMailer) {
            $this->swiftMailer = $this->createMailer();
        }
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
