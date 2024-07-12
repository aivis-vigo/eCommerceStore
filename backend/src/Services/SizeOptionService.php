<?php declare(strict_types=1);

namespace App\Services;

use App\Interfaces\BaseService;
use App\Repositories\Option\SizeOptionRepository;

class SizeOptionService implements BaseService {
    protected SizeOptionRepository $sizeOptionRepository;

    public function __construct()
    {
        $this->sizeOptionRepository = new SizeOptionRepository();
    }

    public function insertOne($entity): void
    {
        $this->getRepository()->insertOne($entity);
    }

    public function getRepository(): SizeOptionRepository
    {
        return $this->sizeOptionRepository;
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