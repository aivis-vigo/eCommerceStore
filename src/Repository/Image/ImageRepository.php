<?php declare(strict_types=1);

namespace App\Repository\Color;

use App\Database\Database;
use App\Interfaces\Product;
use Doctrine\DBAL\Query\QueryBuilder;

// todo: CRUD operations

class ColorRepository
{
    private Database $db;
    private QueryBuilder $queryBuilder;

    public function __construct(Database $db)
    {
        $this->db = $db;
        $this->queryBuilder = $this->db->createQueryBuilder();
    }

    public function insertOne(Product $properties) : void {
        $productItemId = $this->getProductItemId();
        $productId = $this->getProductId($properties->getName());
        $imageId = $this->getImageId($properties->getBrand());

        $this->queryBuilder
            ->insert('product')
            ->values([
                'product_item_id' => ':product_item_id',
                'product_id' => ':product_id',
                'color_id' => ':color_id',
                'original_price' => ':original_price'
            ])
            ->setParameter('product_item_id', $productItemId)
            ->setParameter('product_id', $productId)
            ->setParameter('color_id', $brandId)
            ->setParameter('original_price', $properties->getName())
            ->executeStatement();
    }

    // select last inserted categories id so id's wouldn't overlap in case they are deleted
    public function getProductItemId() : int {
        $count = $this->queryBuilder
            ->select('COUNT(*) as count')
            ->from('product_item')
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
            ->select('product_item_id')
            ->from('product_item')
            ->orderBy('product_item_id', 'DESC')
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchOne();
    }

    public function getProductId(string $name): int {
        return $this->queryBuilder
            ->select('product_id')
            ->from('product')
            ->where('name = :name')
            ->setParameter('name', $name)
            ->executeQuery()
            ->fetchOne();
    }

    public function getImageId(string $brandName): int {
        return $this->queryBuilder
            ->select('brand_id')
            ->from('brand')
            ->where('brand_name = :brand_name')
            ->setParameter('brand_name', $brandName)
            ->executeQuery()
            ->fetchOne();
    }
}