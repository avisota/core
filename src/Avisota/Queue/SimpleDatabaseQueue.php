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

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;

class SimpleDatabaseQueue implements QueueInterface
{
	static public function createTableSchema($tableName)
	{
		$schema = new Schema();
		$table = $schema->createTable($tableName);
		$table->addColumn('id', 'int', array('unsigned' => true, 'autoincrement' => true));
		$table->addColumn('email', 'string', array('length' => 255));
		$table->addColumn('domain', 'string', array('length' => 255));
		$table->addColumn('message', 'string', array('length' => 255));
		$table->addColumn('delivery_date', 'datetime', array('notnull' => false));
		$table->setPrimaryKey(array('id'));
		return $schema;
	}

	/**
	 * @var Connection
	 */
	protected $connection;

	/**
	 * @var string
	 */
	protected $tableName;

	/**
	 * @param Connection $connection The database connection.
	 * @param string     $tableName The name of the database table.
	 * @param bool       $createTableIfNotExists Create the table if not exists.
	 */
	function __construct(Connection $connection, $tableName, $createTableIfNotExists = false)
	{
		$this->connection = $connection;
		$this->tableName  = (string) $tableName;

		$schemaManager = $this->connection->getSchemaManager();
		if (!$schemaManager->tablesExist($this->tableName)) {
			if ($createTableIfNotExists) {
				$schema = static::createTableSchema($this->tableName);
				$queries = $schema->toSql($this->connection->getDatabasePlatform());
				foreach ($queries as $query) {
					$this->connection->exec($query);
				}
			}
			else {
				throw new \RuntimeException('The queue table ' . $this->tableName . ' does not exists!');
			}
		}
	}

	/**
	 * Check if the queue is empty.
	 *
	 * @return bool
	 */
	public function isEmpty()
	{
		return $this->length() == 0;
	}

	/**
	 * Return the length of the queue.
	 *
	 * @return int
	 */
	public function length()
	{
		return (int) $this->connection->fetchColumn(
			'SELECT COUNT(*) FROM ' . $this->connection->quoteIdentifier($this->tableName)
		);
	}

	/**
	 * Return the next message.
	 *
	 * @return \Swift_Message
	 */
	public function next()
	{
		$record = $this->connection->fetchAssoc(
			'SELECT '
		);
	}

	/**
	 * Enqueue a message.
	 *
	 * @param \Swift_Message $message      The message to enqueue.
	 * @param \DateTime      $deliveryDate The message will not delivered until this date is reached.
	 */
	public function enqueue(\Swift_Message $message, \DateTime $deliveryDate = null)
	{
		
	}
}