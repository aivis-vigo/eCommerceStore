<?php declare(strict_types=1);

namespace App\Models\Products\Tech;

use App\Models\Attributes\Attribute;
use App\Models\Attributes\Capacity\ComputerCapacity;
use App\Models\Attributes\TouchId;
use App\Models\Attributes\UsbPorts;

class Computer extends Device
{
    private Attribute $capacity, $usbPorts, $touchID;

    public function __construct(object $properties)
    {
        parent::__construct($properties);
        $this->capacity = new ComputerCapacity($properties->attributes[0]);
        $this->usbPorts = new UsbPorts($properties->attributes[1]);
        $this->touchID = new TouchId($properties->attributes[2]);
    }

    public function getCapacity(): ComputerCapacity
    {
        return $this->capacity;
    }

    public function getUsbPorts(): UsbPorts
    {
        return $this->usbPorts;
    }

    public function getTouchID(): TouchId
    {
        return $this->touchID;
    }

    public function helpDetermineMe(): bool
    {
        return true;
    }

    public function getAttributeArray(): array
    {
        return [
            $this->getUsbPorts(),
            $this->getTouchID(),
            $this->getCapacity()
        ];
    }
}