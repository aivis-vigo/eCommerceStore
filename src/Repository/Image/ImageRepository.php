<?php declare(strict_types=1);

namespace App\Repository\Image;

use App\Repository\BaseRepository;

// todo: mess with how everything is tightly coupled
class ImageRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insertOne(array $image): void
    {
        $productId = $image['productId'];
        $colorName = $image['colorName'];
        $imageUrl = $image['imageUrl'];
        $imageId = $this->getImageId();
        $colorId = $this->getColorId($colorName);
        $productItemId = $this->getProductItemId($productId, $colorId);

        $this->createQueryBuilder()
            ->insert('product_image')
            ->values([
                'image_id' => ':image_id',
                'product_item_id' => ':product_item_id',
                'image_url' => ':image_url'
            ])
            ->setParameter('image_id', $imageId)
            ->setParameter('product_item_id', $productItemId)
            ->setParameter('image_url', $imageUrl)
            ->executeStatement();
    }


    public function findAll(): array
    {
        return $this->createQueryBuilder()
            ->select('*')
            ->from('product_image')
            ->executeQuery()
            ->fetchAssociative();
    }

    public function findOneById(int $product_item_id): array
    {
        return $this->createQueryBuilder()
            ->select('*')
            ->from('product_image')
            ->where('product_item_id = :product_item_id')
            ->setParameter('product_item_id', $product_item_id)
            ->executeQuery()
            ->fetchAllAssociative();
    }

    public function getImageId(): int
    {
        $imageId = $this->createQueryBuilder()
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

    public function getColorId(string|null $colorName): int|null
    {
        if ($colorName) {
            return $this->createQueryBuilder()
                ->select('color_id')
                ->from('color')
                ->where('color_name = :color_name')
                ->setParameter('color_name', $colorName)
                ->executeQuery()
                ->fetchOne();
        }

        return null;
    }

    public function getProductItemId(string $productId, int|null $colorId): int
    {
        if ($colorId) {
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

        return $this->createQueryBuilder()
            ->select('product_item_id')
            ->from('product_item')
            ->where('product_id = :product_id')
            ->setParameter('product_id', $productId)
            ->executeQuery()
            ->fetchOne();
    }
}