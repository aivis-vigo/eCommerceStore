<?php declare(strict_types=1);

namespace App\Services;

use App\Interfaces\BaseService;
use App\Repositories\Category\CategoryRepository;

class CategoryService implements BaseService
{
    protected CategoryRepository $categoryRepository;

    public function __construct()
    {
        $this->categoryRepository = new CategoryRepository();
    }

    public function insertOne($entity): void
    {
        $this->getRepository()->insertOne($entity);
    }

    public function findAll(): array
    {
        return $this->getRepository()->findAll();
    }

    public function findOneById($id): array
    {
        return $this->getRepository()->findOneById($id);
    }

    public function findOneByName($name): array
    {
        return $this->getRepository()->findOneByName($name);
    }

    public function getRepository(): CategoryRepository
    {
        return $this->categoryRepository;
    }
}