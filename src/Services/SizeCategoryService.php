<?php declare(strict_types=1);

namespace App\Services;

use App\Interfaces\BaseService;
use App\Repository\Category\SizeCategoryRepository;

class SizeCategoryService implements BaseService
{
    protected SizeCategoryRepository $sizeCategoryRepository;

    public function __construct()
    {
        $this->sizeCategoryRepository = new SizeCategoryRepository();
    }

    public function insertOne($entity): void
    {
        $this->getRepository()->insertOne($entity);
    }

    public function getRepository(): SizeCategoryRepository
    {
        return $this->sizeCategoryRepository;
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