<?php

/**
 * Avisota newsletter and mailing system
 * Copyright (C) 2013 Tristan Lins
 *
 * PHP version 5
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    avisota-core
 * @license    LGPL-3.0+
 * @filesource
 */

namespace Avisota\Queue;

class QueueExecutionConfig
{
	/**
	 * Limit execution count.
	 *
	 * @var int
	 */
	protected $limit = 0;

	/**
	 * @var QueueExecutionDeciderInterface
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
	 * @param \Avisota\Queue\QueueExecutionDeciderInterface $decider
	 */
	public function setDecider(QueueExecutionDeciderInterface $decider)
	{
		$this->decider = $decider;
		return $this;
	}

	/**
	 * @return \Avisota\Queue\QueueExecutionDeciderInterface
	 */
	public function getDecider()
	{
		return $this->decider;
	}
}