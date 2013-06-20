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

use Avisota\Message\MessageInterface;

/**
 * Class ExecutionDeciderInterface
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