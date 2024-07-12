<?php declare(strict_types=1);

namespace App\Services;

use App\Interfaces\BaseService;
use App\Repositories\Product\ProductRepository;

class ProductService implements BaseService
{
    protected ProductRepository $productRepository;

    public function __construct() {
        $this->productRepository = new ProductRepository();
    }

    public function insertOne($entity): void
    {
        $this->getRepository()->insertOne($entity);
    }

    public function getRepository(): ProductRepository
    {
        return $this->productRepository;
    }

    public function findAll(): array {
        return $this->getRepository()->findAll();
    }

    public function findOneById($id): array {
        return $this->getRepository()->findOneById($id);
    }
}