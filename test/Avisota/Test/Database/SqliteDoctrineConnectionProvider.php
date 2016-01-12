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

namespace Avisota\Test\Database;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

/**
 * Class SqliteDoctrineConnectionProvider
 *
 * @package Avisota\Test\Database
 */
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
