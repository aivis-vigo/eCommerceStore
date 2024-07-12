<?php declare(strict_types=1);

namespace App\Models\Attributes\Capacity;

use App\Models\Attributes\Attribute;

class  ComputerCapacity extends Attribute
{
    public function __construct(object $properties)
    {
        parent::__construct($properties);
    }
}