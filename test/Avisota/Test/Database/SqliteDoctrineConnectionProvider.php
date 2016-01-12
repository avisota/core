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

namespace Avisota\Test\Database;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class SqliteDoctrineConnectionProvider implements DoctrineConnectionProviderInterface
{
    /**
     * @return Connection
     */
    public function createDoctrineConnection()
    {
        $config = new Configuration();

        $connectionParams = array(
            'user'     => 'user',
            'password' => 'secret',
            'memory'   => true,
            'driver'   => 'pdo_sqlite',
        );

        $connection = DriverManager::getConnection($connectionParams, $config);

        return $connection;
    }
}
