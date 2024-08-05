<?php declare(strict_types=1);

namespace App\Repositories\Product;

use App\Interfaces\Product;
use App\Repositories\BaseRepository;

// todo: read and write repos

class ProductVariationRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function findAll(): array
    {
        return $this->createQueryBuilder()
            ->select('*')
            ->from('product_variation')
            ->executeQuery()
            ->fetchAllAssociative();
    }

    public function findAllByProductId(string $productId): array {
        return $this->createQueryBuilder()
            ->select('size_id')
            ->from('product_variation')
            ->where('product_id = :productId')
            ->setParameter('productId', $productId)
            ->executeQuery()
            ->fetchAllAssociative();
    }

    public function findOneById(string $product_id): array
    {
        return $this->createQueryBuilder()
            ->select('*')
            ->from('product_variation')
            ->where('product_id = :product_id')
            ->setParameter('product_id', $product_id)
            ->executeQuery()
            ->fetchAssociative();
    }


    public function insertOne(Product $properties): void
    {
        if (method_exists($properties, 'getSize')) {
            $product_id = $properties->getId();
            $sizeOptions = $properties->getSize()->getOptions();

            foreach ($sizeOptions as $sizeOption) {
                $variationId = $this->getVariationId();
                $sizeId = $this->getSizeId($sizeOption->getValue());

                $this->createQueryBuilder()
                    ->insert('product_variation')
                    ->values([
                        'variation_id' => ':variation_id',
                        'product_id' => ':product_id',
                        'size_id' => ':size_id',
                    ])
                    ->setParameters([
                        'variation_id' => $variationId,
                        'product_id' => $product_id,
                        'size_id' => $sizeId,
                    ])
                    ->executeStatement();
            }
        } else {
            if (method_exists($properties, 'getColor')) {
                $colorOptions = $properties->getColor()->getOptions();

                foreach ($colorOptions as $colorOption) {
                    $product_id = $properties->getId();
                    $variationId = $this->getVariationId();
                    $sizeId = null;

                    $this->createQueryBuilder()
                        ->insert('product_variation')
                        ->values([
                            'variation_id' => ':variation_id',
                            'product_id' => ':product_id',
                            'size_id' => ':size_id',
                        ])
                        ->setParameters([
                            'variation_id' => $variationId,
                            'product_id' => $product_id,
                            'size_id' => $sizeId,
                        ])
                        ->executeStatement();
                }
            }
        }
    }

    public function getVariationId(): int
    {
        $variationId = $this->createQueryBuilder()
            ->select('variation_id')
            ->from('product_variation')
            ->orderBy('variation_id', 'DESC')
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchOne();

        if (!$variationId) {
            return 1;
        }

        return $variationId + 1;
    }

    public function getSizeId(string $size): int
    {
        return $this->createQueryBuilder()
            ->select("size_id")
            ->from("size_options")
            ->where("size_code = :size_code")
            ->setParameter("size_code", $size)
            ->executeQuery()
            ->fetchOne();
    }
}