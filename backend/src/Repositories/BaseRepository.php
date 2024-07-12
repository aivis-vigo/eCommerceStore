<?php declare(strict_types=1);

namespace App\Repositories;

use App\Database\Database;
use Doctrine\DBAL\Query\QueryBuilder;

class BaseRepository
{
    private Database $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getConnection(): Database
    {
        return $this->db;
    }

    public function createQueryBuilder(): QueryBuilder {
        return $this->getConnection()->createQueryBuilder();
    }
}