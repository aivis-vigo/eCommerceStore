<?php declare(strict_types=1);

namespace App\Models\Products\Tech;

use App\Models\Attributes\Attribute;

class Phone extends Device
{
    private Attribute $capacity, $color;

    public function __construct(object $properties)
    {
        parent::__construct($properties);
        $this->capacity = new Attribute($properties->attributes[0]);
        $this->color = new Attribute($properties->attributes[1]);
    }

    public function getCapacity(): Attribute
    {
        return $this->capacity;
    }

    public function getColor(): Attribute
    {
        return $this->color;
    }
}