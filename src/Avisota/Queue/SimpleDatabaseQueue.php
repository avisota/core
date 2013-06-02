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

use Avisota\Message\NativeMessage;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Statement;

class SimpleDatabaseQueue implements QueueInterface
{
	static public function createTableSchema($tableName)
	{
		$schema = new Schema();
		$table  = $schema->createTable($tableName);
		$table->addColumn('id', 'int', array('unsigned' => true, 'autoincrement' => true));
		$table->addColumn('enqueue', 'datetime');
		$table->addColumn('message', 'text');
		$table->addColumn('delivery_date', 'datetime');
		$table->addColumn('reserved', 'bool', array('default' => false));
		$table->setPrimaryKey(array('id'));
		return $schema;
	}

	/**
	 * @var NativeMessage
	 */
	protected $messageSerializer;

	/**
	 * @var Connection
	 */
	protected $connection;

	/**
	 * @var string
	 */
	protected $tableName;

	/**
	 * @param NativeMessage $messageSerializer      The message serializer.
	 * @param Connection        $connection             The database connection.
	 * @param string            $tableName              The name of the database table.
	 * @param bool              $createTableIfNotExists Create the table if not exists.
	 */
	function __construct(NativeMessage $messageSerializer, Connection $connection, $tableName, $createTableIfNotExists = false)
	{
		$this->messageSerializer = $messageSerializer;
		$this->connection = $connection;
		$this->tableName  = (string) $tableName;

		$schemaManager = $this->connection->getSchemaManager();
		if (!$schemaManager->tablesExist($this->tableName)) {
			if ($createTableIfNotExists) {
				$schema  = static::createTableSchema($this->tableName);
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
	 * Set the message serializer.
	 *
	 * @param \Avisota\Message\NativeMessage $messageSerializer
	 */
	public function setMessageSerializer(NativeMessage $messageSerializer)
	{
		$this->messageSerializer = $messageSerializer;
		return $this;
	}

	/**
	 * Get the message serializer.
	 *
	 * @return \Avisota\Message\NativeMessage
	 */
	public function getMessageSerializer()
	{
		return $this->messageSerializer;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isEmpty()
	{
		return $this->length() == 0;
	}

	/**
	 * {@inheritdoc}
	 */
	public function length()
	{
		$queryBuilder = $this->connection->createQueryBuilder();
		/** @var Statement $statement */
		$statement = $query = $queryBuilder
			->select('COUNT(q)')
			->from($this->tableName, 'q')
			->execute();

		return (int) $statement->fetchColumn();
	}

	/**
	 * {@inheritdoc}
	 */
	public function execute(TransportInterface $transport, QueueExecutionConfig $config = null)
	{
		$queryBuilder = $this->connection->createQueryBuilder();
		/** @var Statement $statement */
		$queryBuilder
			->select('q')
			->from($this->tableName, 'q')
			->where('q.reserved=?')
			->andWhere('(q.delivery_date is NULL OR q.delivery_date<=?)')
			->setParameter(1, false)
			->setParameter(2, new \DateTime());

		if ($config) {
			if ($config->getLimit() > 0) {
				$queryBuilder->setMaxResults($config->getLimit());
			}
		}

		$statement = $queryBuilder->execute();

		$record    = $statement->fetch(\PDO::FETCH_ASSOC);

		$message = $this->messageSerializer->unserialize($record['message']);

		return new SimpleDatabaseQueueEntry($message, $record, $this->connection, $this->tableName);
	}

	/**
	 * {@inheritdoc}
	 */
	public function enqueue(\Swift_Message $message, \DateTime $deliveryDate = null)
	{
		$this->connection->insert(
			$this->tableName,
			array(
				 'enqueue'       => new \DateTime(),
				 'message'       => $this->messageSerializer->serialize($message),
				 'delivery_date' => $deliveryDate
			)
		);
	}
}