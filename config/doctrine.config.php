<?php

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDOMySql\Driver;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\XmlDriver;

$dbParams = [
    'database'  => 'mu',
    'username'  => 'root',
    'password'  => 'root123!',
    'hostname'  => 'localhost',
];

return [
    'factories' => [
        'orm_em' => function () use ($dbParams) {
            $connection = new Connection(
                [
                    'host'     => $dbParams['hostname'],
                    'user'     => $dbParams['username'],
                    'password' => $dbParams['password'],
                    'dbname'   => $dbParams['database'],
                ],
                new Driver()
            );

            $configuration = new Configuration();
            $configuration->setMetadataDriverImpl(new XmlDriver('config/doctrine'));
            $configuration->setProxyDir('/tmp');
            $configuration->setProxyNamespace('MuServer');

            return EntityManager::create($connection, $configuration);
        }
    ],
    
];