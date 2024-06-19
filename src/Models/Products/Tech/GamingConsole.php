<?php declare(strict_types=1);

namespace App\Models\Products\Tech;

use App\Models\Attributes\Attribute;

class GamingConsole extends Device
{
    private Attribute $color, $capacity;

    public function __construct(object $properties)
    {
        parent::__construct($properties);
        $this->color = new Attribute($properties->attributes[0]);
        $this->capacity = new Attribute($properties->attributes[1]);
    }

    public function getColor(): Attribute
    {
        return $this->color;
    }

    public function getCapacity(): Attribute
    {
        return $this->capacity;
    }
}