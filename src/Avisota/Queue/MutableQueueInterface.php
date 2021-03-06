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

namespace Avisota\Queue;

use Avisota\Message\MessageInterface;

/**
 * A queue where messages can be dequeued from.
 *
 * @package avisota-core
 */
interface MutableQueueInterface extends QueueInterface
{
    /**
     * Dequeue a message.
     *
     * @param MessageInterface $message The message to dequeue.
     *
     * @return bool
     */
    public function dequeue(MessageInterface $message);
}
