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
 * A message containing a native swift message.
 *
 * @package avisota-core
 */
class NativeMessage implements MessageInterface
{
    /**
     * The swift message.
     *
     * @var \Swift_Message
     */
    protected $message;

    /**
     * @param \Swift_Message $message
     */
    public function __construct(\Swift_Message $message)
    {
        $this->message = $message;
    }

    /**
     * @return array
     */
    public function getRecipients()
    {
        return $this->message->getTo();
    }

    /**
     * @return array
     */
    public function getRecipientDetails()
    {
        return array();
    }

    /**
     * @return array
     */
    public function getCopyRecipients()
    {
        return $this->message->getCc();
    }

    /**
     * @return array
     */
    public function getBlindCopyRecipients()
    {
        return $this->message->getBcc();
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->message->getFrom();
    }

    /**
     * @return string
     */
    public function getSender()
    {
        return $this->message->getSender();
    }

    /**
     * @return string
     */
    public function getReplyTo()
    {
        return $this->message->getReplyTo();
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->message->getSubject();
    }

    /**
     * @return \Swift_Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * String representation of object
     *
     * @link  http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize($this->message);
    }

    /**
     * Constructs the object
     *
     * @link  http://php.net/manual/en/serializable.unserialize.php
     *
     * @param string $serialized <p>
     *                           The string representation of the object.
     *                           </p>
     *
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        $this->message = unserialize($serialized);
    }
}
