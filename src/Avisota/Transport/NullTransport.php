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
 * Transport that simply does nothing.
 *
 * @package avisota-core
 */
class NullTransport implements TransportInterface
{
    /**
     * Initialise transport.
     *
     * @return void
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
        return new TransportStatus($message, 1);
    }

    /**
     * Flush transport.
     *
     * @return void
     */
    public function flush()
    {
    }
}
