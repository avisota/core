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
use Avisota\Queue\QueueInterface;

/**
 * Queuing transport.
 *
 * @package avisota-core
 */
class QueueTransport implements TransportInterface
{
    /**
     * @var QueueInterface
     */
    protected $queue;

    /**
     * @param QueueInterface $queue
     */
    public function __construct(QueueInterface $queue)
    {
        $this->queue = $queue;
    }

    /**
     * @param QueueInterface $queue
     *
     * @return $this
     */
    public function setQueue(QueueInterface $queue)
    {
        $this->queue = $queue;
        return $this;
    }

    /**
     * @return \Swift_Mailer
     */
    public function getQueue()
    {
        return $this->queue;
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
        $this->queue->enqueue($message);
        return new TransportStatus($message, 0, array());
    }
}
