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

namespace Avisota\Test;

use Avisota\Queue\SimpleDatabaseQueue;
use Avisota\Test\Database\SqliteDoctrineConnectionProvider;
use Avisota\Test\Imap\ImapConnectionProvider;
use Avisota\Test\Transport\SmtpTransportProvider;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Statement;

class SmtpTransportSimpleDatabaseQueueTest extends AbstractSimpleDatabaseQueueTest
{
	public function setUp()
	{
		$this->transportProvider = new SmtpTransportProvider();
		$this->imapConnectionProvider = new ImapConnectionProvider();
		$this->doctrineConnectionProvider = new SqliteDoctrineConnectionProvider();
	}
}
