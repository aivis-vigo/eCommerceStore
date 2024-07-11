<?php declare(strict_types=1);

namespace App\Repository\Category;

use App\Repository\BaseRepository;

class SizeCategoryRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insertOne(string $name): void
    {
        $category_id = $this->getCategoryId();

        $this->createQueryBuilder()
            ->insert('size_category')
            ->values([
                'category_id' => ':category_id',
                'category_name' => ':category_name'
            ])
            ->setParameter('category_id', $category_id)
            ->setParameter('category_name', $name)
            ->executeStatement();
    }

    public function findAll(): array
    {
        return $this->createQueryBuilder()
            ->select('*')
            ->from('size_category')
            ->executeQuery()
            ->fetchAssociative();
    }

    public function findOneById(int $category_id): array
    {
        return $this->createQueryBuilder()
            ->select('*')
            ->from('size_category')
            ->where('category_id = :category_id')
            ->setParameter('category_id', $category_id)
            ->executeQuery()
            ->fetchAssociative();
    }

    public function getCategoryId(): int
    {
        $count = $this->createQueryBuilder()
            ->select('COUNT(*) as count')
            ->from('size_category')
            ->executeQuery()
            ->fetchOne();

        if ($count === 0) {
            return 1;
        }

        return $this->getLastEntry() + 1;
    }

    public function getLastEntry(): int
    {
        return $this->createQueryBuilder()
            ->select('category_id')
            ->from('size_category')
            ->orderBy('category_id', 'DESC')
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchOne();
    }
}