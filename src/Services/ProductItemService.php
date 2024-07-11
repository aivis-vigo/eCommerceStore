<?php declare(strict_types=1);

namespace App\Services;

use App\Interfaces\BaseService;
use App\Repository\Product\ProductItemRepository;

class ProductItemService implements BaseService
{
    protected ProductItemRepository $productItemRepository;

    public function __construct()
    {
        $this->productItemRepository = new ProductItemRepository();
    }

    public function insertOne($entity): void
    {
        $this->getRepository()->insertOne($entity);
    }

    public function getRepository(): ProductItemRepository
    {
        return $this->productItemRepository;
    }

    public function findAll(): array
    {
        return $this->getRepository()->findAll();
    }

    public function findOneById($id): array
    {
        return $this->getRepository()->findOneById($id);
    }
}