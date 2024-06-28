<?php declare(strict_types=1);

namespace App\Repository\Image;

use App\Database\Database;
use Doctrine\DBAL\Query\QueryBuilder;

// todo: CRUD operations

class ImageRepository
{
    private Database $db;
    private QueryBuilder $queryBuilder;

    public function __construct(Database $db)
    {
        $this->db = $db;
        $this->queryBuilder = $this->db->createQueryBuilder();
    }

    public function insertOne(string $productId, $imgUrl): void
    {
        $imageId = $this->getImageId();
        $productItemId = $this->getProductItemId($productId);

        $this->db->createQueryBuilder()
            ->insert('product_image')
            ->values([
                'image_id' => ':image_id',
                'product_item_id' => ':product_item_id',
                'image_url' => ':image_url'
            ])
            ->setParameter('image_id', $imageId)
            ->setParameter('product_item_id', $productItemId)
            ->setParameter('image_url', $imgUrl)
            ->executeStatement();
    }

    public function getImageId(): int
    {
        $imageId = $this->db->createQueryBuilder()
            ->select('image_id')
            ->from('product_image')
            ->orderBy('image_id', 'DESC')
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchOne();

        if (!$imageId) {
            return 1;
        }

        return $imageId + 1;
    }

    // todo: images are only assigned to one item not all of them
    public function getProductItemId(string $productId): int
    {
        return $this->db->createQueryBuilder()
            ->select('product_item_id')
            ->from('product_item')
            ->where('product_id = :product_id')
            ->setParameter('product_id', $productId)
            ->executeQuery()
            ->fetchOne();
    }
}