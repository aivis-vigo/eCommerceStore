<?php declare(strict_types=1);

namespace App\Models\Products\Tech;

use App\Interfaces\Product;
use App\Models\Currency\Price;
use App\Models\Products\Brand;

class Device implements Product
{
    private $id,
        $name,
        $available,
        $pictures,
        $description,
        $category,
        $prices,
        $brand;

    public function __construct(object $properties)
    {
        $this->id = $properties->id;
        $this->name = $properties->name;
        $this->available = $properties->inStock;
        $this->pictures = $properties->gallery;
        $this->description = $properties->description;
        $this->category = $properties->category;
        $this->prices = $this->createPrices($properties->prices);
        $this->brand = $properties->brand;
    }

    public function createPrices(array $prices): array
    {
        $allPrices = [];

        foreach ($prices as $price) {
            $currentPrice = new Price($price);

            $allPrices[] = $currentPrice;
        }

        return $allPrices;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isAvailable(): bool
    {
        return $this->available;
    }

    public function getImages(): array
    {
        return $this->pictures;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getPrice(): Object
    {
        return $this->prices[0];
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function getAttributeArray(): array
    {
        return [];
    }
}