<?php declare(strict_types=1);

namespace App\Repository\Option;

use App\Database\Database;
use Doctrine\DBAL\Query\QueryBuilder;

// todo: CRUD operations

class SizeOptionRepository
{
    private Database $db;
    private QueryBuilder $queryBuilder;

    public function __construct(Database $db)
    {
        $this->db = $db;
        $this->queryBuilder = $this->db->createQueryBuilder();
    }

    public function insertOne(int $sizeCategoryId, string $displayValue, string $sizeCode) : void {
        $sizeId = $this->getSizeId();

        $this->queryBuilder
            ->insert('size_options')
            ->values([
                'size_id' => ':size_id',
                'size_category_id' => ':size_category_id',
                'size_display_value' => ':size_display_value',
                'size_code' => ':size_code'
            ])
            ->setParameter('size_id', $sizeId)
            ->setParameter('size_category_id', $sizeCategoryId)
            ->setParameter('size_display_value', $displayValue)
            ->setParameter('size_code', $sizeCode)
            ->executeStatement();
    }

    // select last inserted categories id so id's wouldn't overlap in case they are deleted
    public function getSizeId() : int {
        $count = $this->queryBuilder
            ->select('COUNT(*) as count')
            ->from('size_options')
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
            ->select('size_id')
            ->from('size_options')
            ->orderBy('size_id', 'DESC')
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchNumeric()[0];
    }
}