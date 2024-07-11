<?php declare(strict_types=1);

namespace App\Repository\Product;

use App\Interfaces\Product;
use App\Repository\BaseRepository;

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

    public function insertOne(Product $properties): void
    {
        if (!$this->checkIfExists($properties->getName())) {
            $productId = $properties->getId();
            $productCategoryId = $this->getProductCategoryId($properties->getCategory());
            $brandId = $this->getBrandId($properties->getBrand());
            $name = $properties->getName();
            $description = $properties->getDescription();

            $this->createQueryBuilder()
                ->insert('product')
                ->values([
                    'product_id' => ':product_id',
                    'product_category_id' => ':product_category_id',
                    'brand_id' => ':brand_id',
                    'name' => ':name',
                    'description' => ':description'
                ])
                ->setParameter('product_id', $productId)
                ->setParameter('product_category_id', $productCategoryId)
                ->setParameter('brand_id', $brandId)
                ->setParameter('name', $name)
                ->setParameter('description', $description)
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
}