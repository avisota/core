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

namespace Avisota\Test\Message;

use Avisota\Message\MessageInterface;

/**
 * The basic message interface.
 *
 * @package avisota-core
 */
class TestMessage implements MessageInterface
{
    protected $text;

    /**
     * TestMessage constructor.
     */
    function __construct()
    {
        $this->text = "This is a unit test message.\r\n";
        $this->text .= "timestamp: " . date('Y-m-d H:i:s') . "\r\n";
        $this->text .= "random content: " . mt_rand() . "\r\n";
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return array
     */
    public function getRecipients()
    {
        return array('unittest@avisota.org');
    }

    /**
     * @return array
     */
    public function getRecipientDetails()
    {
        return array(
            'firstname' => 'Unit',
            'surname'   => 'Text',
            'email'     => 'unittest@avisota.org',
        );
    }

    /**
     * @return array
     */
    public function getCopyRecipients()
    {
        return array();
    }

    /**
     * @return array
     */
    public function getBlindCopyRecipients()
    {
        return array();
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return 'unittest@avisota.org';
    }

    /**
     * @return string
     */
    public function getSender()
    {
        return '';
    }

    /**
     * @return string
     */
    public function getReplyTo()
    {
        return '';
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return 'Unit Test test message';
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
        return $this->text;
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
        $this->text = $serialized;
    }
}
