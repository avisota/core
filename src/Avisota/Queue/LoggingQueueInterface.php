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

use Psr\Log\LoggerInterface;

/**
 * A queue that logs its actions.
 *
 * A succeeded transport will be logged as debug message.
 * A partial succeeded transport will be logged as warn message.
 * A failed transport will be logged as error message.
 *
 * @package avisota-core
 */
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
