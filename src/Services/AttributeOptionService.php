<?php declare(strict_types=1);

namespace App\Services;

use App\Interfaces\BaseService;
use App\Repository\Attribute\AttributeOptionRepository;

class AttributeOptionService implements BaseService
{
    protected AttributeOptionRepository $attributeOptionRepository;

    public function __construct()
    {
        $this->attributeOptionRepository = new AttributeOptionRepository();
    }

    public function insertOne($entity): void
    {
        $this->getRepository()->insertOne($entity);
    }

    public function getRepository(): AttributeOptionRepository
    {
        return $this->attributeOptionRepository;
    }

    public function findAll(): array
    {
        return $this->getRepository()->findAll();
    }

    public function findOneById($id): array
    {
        return $this->getRepository()->findOneById($id);
    }

    public function findAllById($id): array
    {
        return $this->getRepository()->findAllById($id);
    }
}