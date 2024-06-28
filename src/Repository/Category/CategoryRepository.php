<?php declare(strict_types=1);

namespace App\Repository\Category;

use App\Database\Database;
use Doctrine\DBAL\Query\QueryBuilder;

// todo: CRUD operations

class CategoryRepository
{
    private Database $db;
    private QueryBuilder $queryBuilder;

    public function __construct(Database $db)
    {
        $this->db = $db;
        $this->queryBuilder = $this->db->createQueryBuilder();
    }

    public function insertOne(string $name) : void {
        $category_id = $this->getCategoryId();

        $this->queryBuilder
            ->insert('product_category')
            ->values([
                'product_category_id' => ':product_category_id',
                'category_name' => ':category_name'
            ])
            ->setParameter('product_category_id', $category_id)
            ->setParameter('category_name', $name)
            ->executeStatement();
    }

    // select last inserted categories id so id's wouldn't overlap in case they are deleted
    public function getCategoryId() : int {
        $count = $this->queryBuilder
            ->select('COUNT(*) as count')
            ->from('product_category')
            ->executeQuery()
            ->fetchOne();

        if ($count === 0) {
            return 1;
        }

        return $this->getLastEntry() + 1;
    }

    // [0] because there will always be only 1 value returned in array
    public function getLastEntry(): int
    {
        return $this->queryBuilder
            ->select('product_category_id')
            ->from('product_category')
            ->orderBy('product_category_id', 'DESC')
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchNumeric()[0];
    }
}