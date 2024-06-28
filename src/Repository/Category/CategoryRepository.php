<?php declare(strict_types=1);

namespace App\Repository\Tech;

use Doctrine\DBAL\Query\QueryBuilder;

class PhoneRepository
{
    private Database $db;
    private QueryBuilder $queryBuilder;

    public function __construct(Database $db)
    {
        $this->db = $db;
        $this->queryBuilder = $this->db->createQueryBuilder();
    }

    public function insertCategory(string $name) : void {
        $category_id = $this->getCategoryCount();

        $this->queryBuilder
            ->insert('size_category')
            ->values([
                'category_id' => ':category_id',
                'category_name' => ':category_name',
            ])
            ->setParameter('category_id', $category_id)
            ->setParameter('category_name', $name)
            ->executeStatement();
    }

    public function getCategoryCount() : int {
        $count = $this->queryBuilder
            ->select('COUNT(*) as count')
            ->from('size_category')
            ->executeQuery()
            ->fetchOne();

        return $count + 1;
    }
}