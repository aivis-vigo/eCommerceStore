<?php declare(strict_types=1);

namespace App\Database;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Query\QueryBuilder;

// todo: move to .env and download a package for that

class Database
{
    private $connection;

    public function __construct()
    {
        $connectionParams = [
            'dbname' => 'ecommerceshop',
            'user' => 'root',
            'password' => 'my-secret-pw',
            'host' => '127.0.0.1',
            'driver' => 'pdo_mysql',
        ];

        $this->connection = DriverManager::getConnection($connectionParams);
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }

    public function createQueryBuilder(): QueryBuilder {
        return $this->connection->createQueryBuilder();
    }
}