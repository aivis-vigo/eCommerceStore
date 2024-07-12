<?php declare(strict_types=1);

namespace App\Services;

use App\Interfaces\BaseService;
use App\Repositories\Attribute\AttributeTypeRepository;

class AttributeTypeService implements BaseService
{
    protected AttributeTypeRepository $attributeTypeRepository;

    public function __construct()
    {
        $this->attributeTypeRepository = new AttributeTypeRepository();
    }

    public function insertOne($entity): void
    {
        $this->getRepository()->insertOne($entity);
    }

    public function getRepository(): AttributeTypeRepository
    {
        return $this->attributeTypeRepository;
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