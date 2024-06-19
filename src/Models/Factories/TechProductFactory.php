<?php declare(strict_types=1);

namespace App\Models\Factories;

use App\Interfaces\Product;
use App\Models\Products\Creator;
use App\Models\Products\Tech\Computer;
use App\Models\Products\Tech\Device;
use App\Models\Products\Tech\GamingConsole;
use App\Models\Products\Tech\Phone;
use ReflectionClass;

class TechProductFactory extends Creator
{
    public function createProduct(object $properties): Product
    {
        $allAttributes = $this->getAllAttributes($properties);

        if (count($allAttributes) > 0) {
            $deviceClass = $this->findMatchingDeviceClass($allAttributes);
            if ($deviceClass !== null) {
                return new $deviceClass($properties);
            }
        }

        // Default devices (Earphones, accessories) - Devices that have the same properties
        return new Device($properties);
    }

    public function getAllAttributes(object $properties): array
    {
        $allAttributes = [];
        $currentProduct = $properties->attributes;

        foreach ($currentProduct as $property) {
            $attributeId = strtolower($property->id);
            if (!in_array($attributeId, $allAttributes)) {
                $allAttributes[] = strtolower($property->id);
            }
        }

        return $allAttributes;
    }

    public function findMatchingDeviceClass(array $allAttrbutes): ?string
    {
        $deviceClasses = [
            Phone::class,
            Computer::class,
            GamingConsole::class
        ];

        foreach ($deviceClasses as $deviceClass) {
            if ($this->classHasAllAttributes($deviceClass, $allAttrbutes)) {
                return $deviceClass;
            }
        }

        return null;
    }

    public function classHasAllAttributes(string $deviceClass, array $allAttributes): bool
    {
        $reflectionClass = new ReflectionClass($deviceClass);

        // class properties have to be in the same order as those in provided data source
        for ($i = 0; $i < count($allAttributes); $i++) {
            if ($allAttributes[$i] !== $reflectionClass->getProperties()[$i]->getName()) {
                // todo: this can be improved
                if (count($allAttributes) === 3 && $reflectionClass->hasMethod('helpDetermineMe')) {
                    return true;
                }

                return false;
            }
        }

        return true;
    }
}