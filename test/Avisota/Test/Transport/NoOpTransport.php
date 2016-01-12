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

namespace Avisota\Test\Transport;

use Avisota\Message\MessageInterface;
use Avisota\Transport\TransportInterface;
use Avisota\Transport\TransportStatus;

class NoOpTransport implements TransportInterface
{
    const SUCCEED = 'succeed';

    const SUCCEED_PARTIAL = 'partial';

    const FAILED = 'failed';

    protected $messages = array();

    protected $succeededStatus;

    function __construct($succeededStatus)
    {
        $this->succeededStatus = $succeededStatus;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Initialise transport.
     */
    public function initialise()
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
        $this->messages[] = $message;

        switch ($this->succeededStatus) {
            case static::SUCCEED:
                return new TransportStatus($message, 1);

            case static::SUCCEED_PARTIAL:
                return new TransportStatus($message, 1, $message->getRecipients());

            case static::FAILED:
                return new TransportStatus($message, 0, $message->getRecipients());
        }
    }

    /**
     * Flush transport.
     */
    public function flush()
    {
    }
}
