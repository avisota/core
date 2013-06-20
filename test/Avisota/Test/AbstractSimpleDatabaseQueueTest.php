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

namespace Avisota\Test;

use Avisota\Queue\SimpleDatabaseQueue;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Statement;

abstract class AbstractSimpleDatabaseQueueTest extends AbstractQueueTest
{
	/**
	 * @var DoctrineConnectionProviderInterface
	 */
	protected $doctrineConnectionProvider;

	protected function createQueue()
	{
		$connection = $this->doctrineConnectionProvider->createDoctrineConnection();
		return new SimpleDatabaseQueue($connection, 'queue', true);
	}

	/**
	 * @expectedException RuntimeException
	 */
	public function testCreateTableException()
	{
		$connection = $this->doctrineConnectionProvider->createDoctrineConnection();
		new SimpleDatabaseQueue($connection, 'queue', false);
	}

	public function testCreateTable()
	{
		$connection = $this->doctrineConnectionProvider->createDoctrineConnection();
		new SimpleDatabaseQueue($connection, 'queue', true);

		$schemaManager = $connection->getSchemaManager();

		$this->assertEquals(
			array('queue'),
			$schemaManager->listTableNames()
		);

		$this->assertEquals(
			array('id', 'enqueue', 'message', 'delivery_date'),
			array_keys($schemaManager->listTableColumns('queue'))
		);
	}
}
