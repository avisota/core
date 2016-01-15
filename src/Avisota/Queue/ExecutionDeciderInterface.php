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
 * The execution decider decide if a message will be send now or delayed
 * for next run.
 *
 * @package avisota-core
 */
interface ExecutionDeciderInterface
{
    /**
     * Check if the message is accepted.
     *
     * @param MessageInterface $message
     *
     * @return bool
     */
    public function accept(MessageInterface $message);
}
