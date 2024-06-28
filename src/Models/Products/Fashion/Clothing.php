<?php declare(strict_types=1);

namespace App\Models\Products\Fashion;

use App\Interfaces\Product;
use App\Models\Attributes\Attribute;
use App\Models\Currency\Price;

class Clothing implements Product
{
    private $id,
        $name,
        $available,
        $pictures,
        $description,
        $category,
        $size,
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
        $this->size = new Attribute($properties->attributes[0]);
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

    public function getPictures(): array
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

    public function getSize(): Attribute
    {
        return $this->size;
    }

    public function getPrices(): array
    {
        return $this->prices;
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