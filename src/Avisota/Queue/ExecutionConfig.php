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
	protected $limit = 0;

	/**
	 * @var ExecutionDeciderInterface
	 */
	protected $decider = null;

	/**
	 * @param int $limit
	 */
	public function setLimit($limit)
	{
		$this->limit = (int) $limit;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getLimit()
	{
		return $this->limit;
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