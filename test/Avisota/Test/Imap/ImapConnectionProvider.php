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

namespace Avisota\Test\Imap;

class ImapConnectionProvider implements ImapConnectionProviderInterface
{
	/**
	 * @return ressource
	 */
	public function createImapConnection()
	{
		$host       = array_key_exists('AVISOTA_TEST_IMAP_HOST', $_ENV) ? $_ENV['AVISOTA_TEST_IMAP_HOST'] : getenv('AVISOTA_TEST_IMAP_HOST');
		$port       = array_key_exists('AVISOTA_TEST_IMAP_PORT', $_ENV) ? $_ENV['AVISOTA_TEST_IMAP_PORT'] : getenv('AVISOTA_TEST_IMAP_PORT');
		$protocol   = array_key_exists('AVISOTA_TEST_IMAP_PROTOCOL', $_ENV) ? $_ENV['AVISOTA_TEST_IMAP_PROTOCOL'] : getenv('AVISOTA_TEST_IMAP_PROTOCOL');
		$username   = array_key_exists('AVISOTA_TEST_IMAP_USERNAME', $_ENV) ? $_ENV['AVISOTA_TEST_IMAP_USERNAME'] : getenv('AVISOTA_TEST_IMAP_USERNAME');
		$password   = array_key_exists('AVISOTA_TEST_IMAP_PASSWORD', $_ENV) ? $_ENV['AVISOTA_TEST_IMAP_PASSWORD'] : getenv('AVISOTA_TEST_IMAP_PASSWORD');
		$encryption = array_key_exists('AVISOTA_TEST_IMAP_ENCRYPTION', $_ENV) ? $_ENV['AVISOTA_TEST_IMAP_ENCRYPTION'] : getenv('AVISOTA_TEST_IMAP_ENCRYPTION');

		if ($host && $username && $password) {
			return imap_open(
				'{' . $host . ($port ? ':' . $port : '') . '/' . ($protocol ? $protocol : 'imap') . ($encryption
					? '/' . $encryption . '/novalidate-cert' : '') . '}',
				$username,
				$password
			);
		}

		return false;
	}
}