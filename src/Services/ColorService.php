<?php declare(strict_types=1);

namespace App\Services;

use App\Interfaces\BaseService;
use App\Repository\Color\ColorRepository;

class ColorService implements BaseService
{
    protected ColorRepository $colorRepository;

    public function __construct()
    {
        $this->colorRepository = new ColorRepository();
    }

    public function insertOne($entity): void
    {
        $this->getRepository()->insertOne($entity);
    }

    public function getRepository(): ColorRepository
    {
        return $this->colorRepository;
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