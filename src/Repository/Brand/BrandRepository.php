<?php declare(strict_types=1);

namespace App\Repository\Brand;

use App\Repository\BaseRepository;

class BrandRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function findAll(): array
    {
        return $this->createQueryBuilder()
            ->select('*')
            ->from('brand')
            ->executeQuery()
            ->fetchAssociative();
    }

    public function findOneById(int $brand_id): array
    {
        return $this->createQueryBuilder()
            ->select('*')
            ->from('brand')
            ->where('brand_id = :brand_id')
            ->setParameter('brand_id', $brand_id)
            ->executeQuery()
            ->fetchAssociative();
    }

    // if brand doesn't exist create new one or use existing one
    public function insertOne(string $name): void
    {
        if (!$this->checkIfExists($name)) {
            $brandId = $this->getBrandId();

            $this->createQueryBuilder()
                ->insert('brand')
                ->values([
                    'brand_id' => ':brand_id',
                    'brand_name' => ':brand_name'
                ])
                ->setParameter('brand_id', $brandId)
                ->setParameter('brand_name', $name)
                ->executeStatement();
        }
    }

    public function checkIfExists(string $name): bool
    {
        $brandId = $this->createQueryBuilder()
            ->select('brand_id')
            ->from('brand')
            ->where('brand_name = :brand_name')
            ->setParameter('brand_name', $name)
            ->executeQuery()
            ->fetchOne();

        if ($brandId) {
            return true;
        }

        return false;
    }

    // select last inserted categories id so id's wouldn't overlap in case they are deleted
    public function getBrandId(): int
    {
        $count = $this->createQueryBuilder()
            ->select('COUNT(*) as count')
            ->from('brand')
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
            ->select('brand_id')
            ->from('brand')
            ->orderBy('brand_id', 'DESC')
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchNumeric()[0];
    }
}