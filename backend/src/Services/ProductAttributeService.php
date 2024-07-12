<?php declare(strict_types=1);

namespace App\Services;

use App\Interfaces\BaseService;
use App\Repositories\Attribute\ProductAttributeRepository;

class ProductAttributeService implements BaseService {
    protected ProductAttributeRepository $productAttributeRepository;

    public function __construct()
    {
        $this->productAttributeRepository = new ProductAttributeRepository();
    }

    public function insertOne($entity): void
    {
        $this->getRepository()->insertOne($entity);
    }

    public function getRepository(): ProductAttributeRepository
    {
        return $this->productAttributeRepository;
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