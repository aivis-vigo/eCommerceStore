<?php declare(strict_types=1);

namespace App\Models\Attributes;

class  PhoneCapacity extends Attribute
{
    public function __construct(object $properties)
    {
        parent::__construct($properties);
    }
}