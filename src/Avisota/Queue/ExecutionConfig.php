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

namespace Avisota\Queue;

/**
 * The execution config for a queue.
 *
 * @package avisota-core
 */
class ExecutionConfig
{
	/**
	 * Limit execution count.
	 *
	 * @var int
	 */
	protected $messageLimit = 0;

	/**
	 * Limit execution time in seconds.
	 *
	 * @var int
	 */
	protected $timeLimit = 0;

	/**
	 * @var ExecutionDeciderInterface
	 */
	protected $decider = null;

	/**
	 * @param int $messageLimit
	 */
	public function setMessageLimit($messageLimit)
	{
		$this->messageLimit = (int) $messageLimit;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getMessageLimit()
	{
		return $this->messageLimit;
	}

	/**
	 * @param int $timeLimit
	 */
	public function setTimeLimit($timeLimit)
	{
		$this->timeLimit = $timeLimit;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getTimeLimit()
	{
		return $this->timeLimit;
	}

	/**
	 * @param \Avisota\Queue\ExecutionDeciderInterface $decider
	 */
	public function setDecider(ExecutionDeciderInterface $decider)
	{
		$this->decider = $decider;
		return $this;
	}

	/**
	 * @return \Avisota\Queue\ExecutionDeciderInterface
	 */
	public function getDecider()
	{
		return $this->decider;
	}
}