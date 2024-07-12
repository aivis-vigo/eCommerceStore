<?php declare(strict_types=1);

namespace App\Models\Categories;

abstract class Category
{
    abstract public function getName(): string;
}