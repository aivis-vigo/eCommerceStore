<?php declare(strict_types=1);

namespace App\Models\Products\Tech;

use App\Models\Attributes\Attribute;
use App\Models\Attributes\Capacity\PhoneCapacity;
use App\Models\Attributes\Color;

class Phone extends Device
{
    private Attribute $capacity, $color;

    public function __construct(object $properties)
    {
        parent::__construct($properties);
        $this->capacity = new PhoneCapacity($properties->attributes[0]);
        $this->color = new Color($properties->attributes[1]);
    }

    public function getCapacity(): PhoneCapacity
    {
        return $this->capacity;
    }

    public function getColor(): Attribute
    {
        return $this->color;
    }

    public function getAttributeArray(): array {
        return [$this->getCapacity(), $this->getColor()];
    }
}