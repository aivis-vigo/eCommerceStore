<?php declare(strict_types=1);

namespace App\Services;

use App\Interfaces\BaseService;
use App\Repositories\Image\ImageRepository;
use App\Repositories\Product\ProductVariationRepository;

class ProductVariationService implements BaseService
{
    protected ProductVariationRepository $productVariationRepository;

    public function __construct()
    {
        $this->productVariationRepository = new ProductVariationRepository();
    }

    public function insertOne($entity): void
    {
        $this->getRepository()->insertOne($entity);
    }

    public function getRepository(): ProductVariationRepository
    {
        return $this->productVariationRepository;
    }

    public function findAll(): array
    {
        return $this->getRepository()->findAll();
    }

    public function findAllByProductId($id): array {
        return $this->getRepository()->findAllByProductId($id);
    }

    public function findOneById($id): array
    {
        return $this->getRepository()->findOneById($id);
    }
}