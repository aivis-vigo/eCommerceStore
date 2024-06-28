<?php declare(strict_types=1);

namespace App\Models\Products\Tech;

use App\Models\Attributes\Attribute;
use App\Models\Attributes\Capacity\ConsoleCapacity;

class GamingConsole extends Device
{
    private Attribute $color, $capacity;

    public function __construct(object $properties)
    {
        parent::__construct($properties);
        $this->color = new Attribute($properties->attributes[0]);
        $this->capacity = new ConsoleCapacity($properties->attributes[1]);
    }

    public function getColor(): Attribute
    {
        return $this->color;
    }

    public function getCapacity(): ConsoleCapacity
    {
        return $this->capacity;
    }

    public function getAttributeArray(): array {
        return [$this->getCapacity()];
    }
}