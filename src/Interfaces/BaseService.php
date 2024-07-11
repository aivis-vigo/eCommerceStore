<?php declare(strict_types=1);

namespace App\Interfaces;

use App\Repository\BaseRepository;

interface BaseService
{
    public function getRepository(): BaseRepository;
    public function insertOne($entity): void;
    public function findAll(): array;
    public function findOneById($id): ?array;
}