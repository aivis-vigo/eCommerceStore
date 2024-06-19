<?php declare(strict_types=1);

namespace App\Models\Products\Tech;

use App\Models\Attributes\Attribute;

class Computer extends Device
{
    private Attribute $capacity, $usbPorts, $touchID;

    public function __construct(object $properties)
    {
        parent::__construct($properties);
        $this->capacity = new Attribute($properties->attributes[0]);
        $this->usbPorts = new Attribute($properties->attributes[1]);
        $this->touchID = new Attribute($properties->attributes[2]);
    }

    public function getCapacity(): Attribute
    {
        return $this->capacity;
    }

    public function getUsbPorts(): Attribute
    {
        return $this->usbPorts;
    }

    public function getTouchID(): Attribute
    {
        return $this->touchID;
    }

    public function helpDetermineMe(): bool
    {
        return true;
    }
}