<?php declare(strict_types=1);

namespace App\Repository\Category;

use App\Repository\BaseRepository;

class CategoryRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insertOne(string $name): void
    {
        $category_id = $this->getCategoryId();

        $this->createQueryBuilder()
            ->insert('product_category')
            ->values([
                'product_category_id' => ':product_category_id',
                'category_name' => ':category_name'
            ])
            ->setParameter('product_category_id', $category_id)
            ->setParameter('category_name', $name)
            ->executeStatement();
    }

    public function findAll(): array
    {
        return $this->createQueryBuilder()
            ->select('*')
            ->from('product_category')
            ->executeQuery()
            ->fetchAssociative();
    }

    public function findOneById(int $product_category_id): array
    {
        return $this->createQueryBuilder()
            ->select('*')
            ->from('product_category')
            ->where('product_category_id = :product_category_id')
            ->setParameter('product_category_id', $product_category_id)
            ->executeQuery()
            ->fetchAssociative();
    }

    // select last inserted categories id so id's wouldn't overlap in case they are deleted
    public function getCategoryId(): int
    {
        $count = $this->createQueryBuilder()
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
        return $this->createQueryBuilder()
            ->select('product_category_id')
            ->from('product_category')
            ->orderBy('product_category_id', 'DESC')
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchNumeric()[0];
    }
}