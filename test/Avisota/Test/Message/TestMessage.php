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

class TestMessage implements MessageInterface
{
    protected $text;

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
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function getCopyRecipients()
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function getBlindCopyRecipients()
    {
        return array();
    }

    /**
     * {@inheritdoc}
     */
    public function getFrom()
    {
        return 'unittest@avisota.org';
    }

    /**
     * {@inheritdoc}
     */
    public function getSender()
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function getReplyTo()
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function getSubject()
    {
        return 'Unit Test test message';
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return $this->text;
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        $this->text = $serialized;
    }
}
