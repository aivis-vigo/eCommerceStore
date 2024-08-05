<?php declare(strict_types=1);

namespace App\Repositories\Option;

use App\Repositories\BaseRepository;

class SizeOptionRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insertOne(array $sizeOption): void
    {
        $sizeId = $this->getSizeId();

        $this->createQueryBuilder()
            ->insert('size_options')
            ->values([
                'size_id' => ':size_id',
                'size_category_id' => ':size_category_id',
                'size_display_value' => ':size_display_value',
                'size_code' => ':size_code'
            ])
            ->setParameter('size_id', $sizeId)
            ->setParameter('size_category_id', $sizeOption['sizeCategoryId'])
            ->setParameter('size_display_value', $sizeOption['displayValue'])
            ->setParameter('size_code', $sizeOption['value'])
            ->executeStatement();
    }

    public function findAll(): array
    {
        return $this->createQueryBuilder()
            ->select('*')
            ->from('size_options')
            ->executeQuery()
            ->fetchAssociative();
    }

    public function findAllByCategoryId(int $category_id): array
    {
        return $this->createQueryBuilder()
            ->select('*')
            ->from('size_options')
            ->where('size_category_id = :size_category_id')
            ->setParameter('size_category_id', $category_id)
            ->executeQuery()
            ->fetchAllAssociative();
    }

    public function findOneById(int $sizeId): array
    {
        return $this->createQueryBuilder()
            ->select('*')
            ->from('size_options')
            ->where('size_id = :size_id')
            ->setParameter('size_id', $sizeId)
            ->executeQuery()
            ->fetchAssociative();
    }

    // select last inserted categories id so id's wouldn't overlap in case they are deleted
    public function getSizeId(): int
    {
        $count = $this->createQueryBuilder()
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
        return $this->createQueryBuilder()
            ->select('size_id')
            ->from('size_options')
            ->orderBy('size_id', 'DESC')
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchNumeric()[0];
    }
}