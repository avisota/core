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

namespace Avisota\Test\Queue;

use Avisota\Message\MessageInterface;
use Avisota\Queue\ExecutionDeciderInterface;

class TestQueueExecutionDecider implements ExecutionDeciderInterface
{
    protected $hits = 0;

    protected $accept;

    function __construct($accept)
    {
        $this->accept = (bool) $accept;
    }

    /**
     * @return mixed
     */
    public function getHits()
    {
        return $this->hits;
    }

    /**
     * Check if the message is accepted.
     *
     * @param MessageInterface $message
     *
     * @return bool
     */
    public function accept(MessageInterface $message)
    {
        $this->hits++;
        return $this->accept;
    }
}
