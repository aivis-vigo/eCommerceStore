<?php declare(strict_types=1);

namespace App\Services;

use App\Interfaces\BaseService;
use App\Repositories\Brand\BrandRepository;

class BrandService implements BaseService
{
    protected BrandRepository $brandRepository;

    public function __construct()
    {
        $this->brandRepository = new BrandRepository();
    }

    public function insertOne($entity): void
    {
        $this->getRepository()->insertOne($entity);
    }

    public function getRepository(): BrandRepository
    {
        return $this->brandRepository;
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