<?php declare(strict_types=1);

namespace App\Repositories\Product;

use App\Interfaces\Product;
use App\Repositories\BaseRepository;

// todo: make exception for Product not found
class ProductRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function findAll(): array
    {
        return $this->createQueryBuilder()
            ->select('*')
            ->from('product')
            ->executeQuery()
            ->fetchAllAssociative();
    }

    public function findOneById(string $product_id): array
    {
        return $this->createQueryBuilder()
            ->select('*')
            ->from('product')
            ->where('product_id = :product_id')
            ->setParameter('product_id', $product_id)
            ->executeQuery()
            ->fetchAssociative();
    }

    public function findAllByCategoryId(int $product_category_id): array {
        return $this->createQueryBuilder()
            ->select('*')
            ->from('product')
            ->where('product_category_id = :product_category_id')
            ->setParameter('product_category_id', $product_category_id)
            ->executeQuery()
            ->fetchAllAssociative();
    }

    public function insertOne(Product $properties): void
    {
        if (!$this->checkIfExists($properties->getName())) {
            $productId = $properties->getId();
            $productCategoryId = $this->getProductCategoryId($properties->getCategory());
            $brandId = $this->getBrandId($properties->getBrand());
            $name = $properties->getName();
            $description = $properties->getDescription();
            $inStock = $properties->isAvailable() ? 1 : 0;
            $originalPrice = $properties->getPrice()->getAmount();
            $formattedPrice = $this->formatPrice($originalPrice);

            $this->createQueryBuilder()
                ->insert('product')
                ->values([
                    'product_id' => ':product_id',
                    'product_category_id' => ':product_category_id',
                    'brand_id' => ':brand_id',
                    'name' => ':name',
                    'description' => ':description',
                    'in_stock' => ':in_stock',
                    'original_price' => ':original_price'
                ])
                ->setParameters([
                    'product_id' => $productId,
                    'product_category_id' => $productCategoryId,
                    'brand_id' => $brandId,
                    'name' => $name,
                    'description' => $description,
                    'in_stock' => $inStock,
                    'original_price' => $formattedPrice
                ])
                ->executeStatement();
        }
    }

    public function checkIfExists(string $name): bool
    {
        $brandId = $this->createQueryBuilder()
            ->select('product_id')
            ->from('product')
            ->where('name = :name')
            ->setParameter('name', $name)
            ->executeQuery()
            ->fetchOne();

        if ($brandId) {
            return true;
        }

        return false;
    }

    public function getProductCategoryId(string $category): int
    {
        return $this->createQueryBuilder()
            ->select('product_category_id')
            ->from('product_category')
            ->where('category_name = :category_name')
            ->setParameter('category_name', $category)
            ->executeQuery()
            ->fetchOne();
    }

    public function getBrandId(string $brandName): int
    {
        return $this->createQueryBuilder()
            ->select('brand_id')
            ->from('brand')
            ->where('brand_name = :brand_name')
            ->setParameter('brand_name', $brandName)
            ->executeQuery()
            ->fetchOne();
    }

    // todo: Utils functions?
    public function formatPrice(float $price): int
    {
        return (int)($price * 100);
    }
}