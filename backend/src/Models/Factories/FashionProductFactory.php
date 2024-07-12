<?php declare(strict_types=1);

namespace App\Models\Factories;

use App\Interfaces\Product;
use App\Models\Products\Creator;
use App\Models\Products\Fashion\Clothing;

class FashionProductFactory extends Creator
{
    public function createProduct(object $properties): Product
    {
        return new Clothing($properties);
    }
}