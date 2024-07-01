<?php declare(strict_types=1);

namespace App\Models;

class DataSource
{
    private $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getData(): object
    {
        $data = file_get_contents($this->getPath());
        return json_decode($data)->data;
    }

    public function getCategories(): array
    {
        return $this->getData()->categories;
    }

    public function getProducts(): array
    {
        return $this->getData()->products;
    }
}