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

/**
 * A queue that archive send messages and provide information about
 * successfully and faulty messages.
 *
 * @package avisota-core
 */
interface ArchivingQueueInterface extends QueueInterface
{
    /**
     * Clean transported message information.
     *
     * @return bool
     */
    public function cleanup();

    /**
     * Return the count of send messages.
     *
     * @return int
     */
    public function sendCount();

    /**
     * Return the successfully send messages.
     *
     * @return ArchivingQueueEntryInterface
     */
    public function successfullyMessages();

    /**
     * Return the faulty send messages.
     *
     * @return int
     */
    public function faultyMessages();
}
