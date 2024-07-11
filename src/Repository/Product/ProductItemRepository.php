<?php declare(strict_types=1);

namespace App\Repository\Product;

use App\Interfaces\Product;
use App\Repository\BaseRepository;

class ProductItemRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function findAll(): array
    {
        return $this->createQueryBuilder()
            ->select('*')
            ->from('product_item')
            ->executeQuery()
            ->fetchAssociative();
    }

    public function findOneById(string $product_id): array
    {
        return $this->createQueryBuilder()
            ->select('*')
            ->from('product_item')
            ->where('product_id = :product_id')
            ->setParameter('product_id', $product_id)
            ->executeQuery()
            ->fetchAllAssociative();
    }

    public function insertOne(Product $properties): void
    {
        $productId = $properties->getId();
        $floatPrice = $properties->getPrices()[0]->getAmount();
        $originalPrice = $this->formatPrice($floatPrice);

        if (method_exists($properties, "getColor")) {
            $colors = $properties->getColor()->getOptions();
            foreach ($colors as $color) {
                $productItemId = $this->getProductItemId();
                $colorId = $this->getColorId($color->getDisplayValue());
                $this->createQueryBuilder()
                    ->insert('product_item')
                    ->values([
                        'product_item_id' => ':product_item_id',
                        'product_id' => ':product_id',
                        'color_id' => ':color_id',
                        'original_price' => ':original_price'
                    ])
                    ->setParameter('product_item_id', $productItemId)
                    ->setParameter('product_id', $productId)
                    ->setParameter('color_id', $colorId)
                    ->setParameter('original_price', $originalPrice)
                    ->executeStatement();
            }
        } else {
            $productItemId = $this->getProductItemId();
            $this->createQueryBuilder()
                ->insert('product_item')
                ->values([
                    'product_item_id' => ':product_item_id',
                    'product_id' => ':product_id',
                    'color_id' => ':color_id',
                    'original_price' => ':original_price'
                ])
                ->setParameter('product_item_id', $productItemId)
                ->setParameter('product_id', $productId)
                ->setParameter('color_id', null)
                ->setParameter('original_price', $originalPrice)
                ->executeStatement();
        }
    }

    public function formatPrice(float $price): int
    {
        return (int)($price * 100);
    }

    public function getProductItemId(): int
    {
        $productItemId = $this->createQueryBuilder()
            ->select('product_item_id')
            ->from('product_item')
            ->orderBy('product_item_id', 'DESC')
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchOne();

        if (!$productItemId) {
            return 1;
        }

        return $productItemId + 1;
    }

    public function getColorId(string $colorName): int
    {
        return $this->createQueryBuilder()
            ->select('color_id')
            ->from('color')
            ->where('color_name = :color_name')
            ->setParameter('color_name', $colorName)
            ->executeQuery()
            ->fetchOne();
    }
}