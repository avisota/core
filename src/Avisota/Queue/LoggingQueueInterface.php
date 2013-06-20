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

use Psr\Log\LoggerInterface;

interface LoggingQueueInterface
{
	/**
	 * Set the logger for this queue.
	 *
	 * @param LoggerInterface $logger
	 *
	 * @return QueueInterface
	 */
	public function setLogger(LoggerInterface $logger = null);

	/**
	 * Get the logger for this queue.
	 *
	 * @return LoggerInterface|null
	 */
	public function getLogger();
}
