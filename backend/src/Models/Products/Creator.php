<?php declare(strict_types=1);

namespace App\Models\Products;

use App\Interfaces\Product;

abstract class Creator
{
    abstract public function createProduct(object $item): Product;
}