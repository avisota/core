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

namespace Avisota\Test\Transport;

use Avisota\Transport\SwiftSmtpTransport;

class SmtpTransportProvider implements TransportProviderInterface
{
	/**
	 * @return \Swift_Transport
	 */
	public function createTransport()
	{
		$host       = array_key_exists('AVISOTA_TEST_SMTP_HOST', $_ENV) ? $_ENV['AVISOTA_TEST_SMTP_HOST'] : getenv('AVISOTA_TEST_SMTP_HOST');
		$port       = array_key_exists('AVISOTA_TEST_SMTP_PORT', $_ENV) ? $_ENV['AVISOTA_TEST_SMTP_PORT'] : getenv('AVISOTA_TEST_SMTP_PORT');
		$username   = array_key_exists('AVISOTA_TEST_SMTP_USERNAME', $_ENV) ? $_ENV['AVISOTA_TEST_SMTP_USERNAME'] : getenv('AVISOTA_TEST_SMTP_USERNAME');
		$password   = array_key_exists('AVISOTA_TEST_SMTP_PASSWORD', $_ENV) ? $_ENV['AVISOTA_TEST_SMTP_PASSWORD'] : getenv('AVISOTA_TEST_SMTP_PASSWORD');
		$encryption = array_key_exists('AVISOTA_TEST_SMTP_ENCRYPTION', $_ENV) ? $_ENV['AVISOTA_TEST_SMTP_ENCRYPTION'] : getenv('AVISOTA_TEST_SMTP_ENCRYPTION');

		if ($host && $username && $password) {
			return new SwiftSmtpTransport($host, $port, $username, $password, $encryption);
		}

		return false;
	}
}