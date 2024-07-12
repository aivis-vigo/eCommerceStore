<?php declare(strict_types=1);

namespace App\Models\Attributes;

class Option
{
    private string $id, $value, $displayValue;

    public function __construct(object $properties)
    {
        $this->id = $properties->id;
        $this->value = $properties->value;
        $this->displayValue = $properties->displayValue;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getDisplayValue(): string
    {
        return $this->displayValue;
    }
}