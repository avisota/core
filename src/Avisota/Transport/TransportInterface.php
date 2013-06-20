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

namespace Avisota\Transport;

use Avisota\Message\MessageInterface;

/**
 * Class AvisotaTransportModule
 *
 *
 * @copyright  bit3 UG 2013
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @package    Avisota
 */
interface TransportInterface
{
	/**
	 * Initialise transport.
	 */
	public function initialise();

	/**
	 * Transport a message.
	 *
	 * @param MessageInterface $message
	 * @return TransportStatus
	 */
	public function transport(MessageInterface $message);

	/**
	 * Flush transport.
	 */
	public function flush();
}
