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

/**
 * The transport interface.
 *
 * @package avisota-core
 */
interface TransportInterface
{
    /**
     * Initialise transport.
     *
     * @return void
     */
    public function initialise();

    /**
     * Transport a message.
     *
     * @param MessageInterface $message
     *
     * @return TransportStatus
     */
    public function send(MessageInterface $message);

    /**
     * Flush transport.
     *
     * @return void
     */
    public function flush();
}
