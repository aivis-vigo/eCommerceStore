<?php declare(strict_types=1);

namespace App\Repositories\Image;

use App\Repositories\BaseRepository;

class ImageRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insertOne(array $image): void
    {
        $imageId = $this->getImageId();
        $productId = $image['productId'];
        $imageUrl = $image['imageUrl'];

        $this->createQueryBuilder()
            ->insert('product_image')
            ->values([
                'image_id' => ':image_id',
                'product_id' => ':product_id',
                'image_url' => ':image_url'
            ])
            ->setParameter('image_id', $imageId)
            ->setParameter('product_id', $productId)
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

    public function findOneById(string $product_id): array
    {
        return $this->createQueryBuilder()
            ->select('*')
            ->from('product_image')
            ->where('product_id = :product_id')
            ->setParameter('product_id', $product_id)
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
}