<?php declare(strict_types=1);

namespace App\Services;

use App\Interfaces\BaseService;
use App\Repository\Image\ImageRepository;

class ImageService implements BaseService
{
    protected ImageRepository $imageRepository;

    public function __construct()
    {
        $this->imageRepository = new ImageRepository();
    }

    public function insertOne($entity): void
    {
        $this->getRepository()->insertOne($entity);
    }

    public function getRepository(): ImageRepository
    {
        return $this->imageRepository;
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