<?php

/**
 * Avisota newsletter and mailing system
 *
 * PHP Version 5.3
 *
 * @copyright  way.vision 2015
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @package    avisota-core
 * @license    LGPL-3.0+
 * @link       http://avisota.org
 */

namespace Avisota\Test;

use Avisota\Queue\SimpleDatabaseQueue;
use Avisota\Test\Database\DoctrineConnectionProviderInterface;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Statement;

/**
 * Class AbstractSimpleDatabaseQueueTest
 *
 * @package Avisota\Test
 */
abstract class AbstractSimpleDatabaseQueueTest extends AbstractQueueTest
{
    /**
     * @var DoctrineConnectionProviderInterface
     */
    protected $doctrineConnectionProvider;

    /**
     * @return SimpleDatabaseQueue
     */
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
