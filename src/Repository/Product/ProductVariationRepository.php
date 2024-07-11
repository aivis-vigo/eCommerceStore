<?php declare(strict_types=1);

namespace App\Repository\Product;

use App\Interfaces\Product;
use App\Repository\BaseRepository;

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
            ->fetchAssociative();
    }

    public function findOneById(int $product_item_id): array
    {
        return $this->createQueryBuilder()
            ->select('*')
            ->from('product_variation')
            ->where('product_item_id = :product_item_id')
            ->setParameter('product_item_id', $product_item_id)
            ->executeQuery()
            ->fetchAssociative();
    }


    public function insertOne(Product $properties): void
    {
        if (method_exists($properties, 'getSize')) {
            $productItemId = $this->getProductItemId($properties->getId());
            $sizeOptions = $properties->getSize()->getOptions();
            $inStock = $properties->isAvailable();

            foreach ($sizeOptions as $sizeOption) {
                $variationId = $this->getVariationId();
                $sizeId = $this->getSizeId($sizeOption->getValue());

                $this->createQueryBuilder()
                    ->insert('product_variation')
                    ->values([
                        'variation_id' => ':variation_id',
                        'product_item_id' => ':product_item_id',
                        'size_id' => ':size_id',
                        'in_stock' => ':in_stock'
                    ])
                    ->setParameter('variation_id', $variationId)
                    ->setParameter('product_item_id', $productItemId)
                    ->setParameter('size_id', $sizeId)
                    ->setParameter('in_stock', $inStock)
                    ->executeStatement();
            }
        } else {
            if (method_exists($properties, 'getColor')) {
                $colorOptions = $properties->getColor()->getOptions();
                $inStock = $properties->isAvailable() ? 1 : 0;

                foreach ($colorOptions as $colorOption) {
                    $productItemId = $this->getProductItemId($properties->getId(), $colorOption->getValue());
                    $variationId = $this->getVariationId();
                    $sizeId = null;

                    $this->createQueryBuilder()
                        ->insert('product_variation')
                        ->values([
                            'variation_id' => ':variation_id',
                            'product_item_id' => ':product_item_id',
                            'size_id' => ':size_id',
                            'in_stock' => ':in_stock'
                        ])
                        ->setParameter('variation_id', $variationId)
                        ->setParameter('product_item_id', $productItemId)
                        ->setParameter('size_id', $sizeId)
                        ->setParameter('in_stock', $inStock)
                        ->executeStatement();
                }
            } else {
                $variationId = $this->getVariationId();
                $productItemId = $this->getProductItemId($properties->getId());
                $inStock = $properties->isAvailable() ? 1 : 0;
                $sizeId = null;

                $this->createQueryBuilder()
                    ->insert('product_variation')
                    ->values([
                        'variation_id' => ':variation_id',
                        'product_item_id' => ':product_item_id',
                        'size_id' => ':size_id',
                        'in_stock' => ':in_stock'
                    ])
                    ->setParameter('variation_id', $variationId)
                    ->setParameter('product_item_id', $productItemId)
                    ->setParameter('size_id', $sizeId)
                    ->setParameter('in_stock', $inStock)
                    ->executeStatement();
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

    public function getProductItemId(string $productId, string $hexValue = null): int
    {
        // for tech products
        if ($hexValue) {
            $colorId = $this->getColorId($hexValue);

            return $this->createQueryBuilder()
                ->select('product_item_id')
                ->from('product_item')
                ->where('product_id = :product_id')
                ->andWhere('color_id = :color_id')
                ->setParameter('product_id', $productId)
                ->setParameter('color_id', $colorId)
                ->executeQuery()
                ->fetchOne();
        }

        // for fashion products
        return $this->createQueryBuilder()
            ->select('product_item_id')
            ->from('product_item')
            ->where('product_id = :product_id')
            ->setParameter('product_id', $productId)
            ->executeQuery()
            ->fetchOne();
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

    public function getColorId(string $colorHexValue): int
    {
        return $this->createQueryBuilder()
            ->select('color_id')
            ->from('color')
            ->where('color_hex_value = :color_hex_value')
            ->setParameter('color_hex_value', $colorHexValue)
            ->executeQuery()
            ->fetchOne();
    }
}