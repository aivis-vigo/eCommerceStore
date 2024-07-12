<?php declare(strict_types=1);

namespace App\Models\Currency;

class Currency
{
    private $label, $symbol;

    public function __construct(object $properties)
    {
        $this->label = $properties->label;
        $this->symbol = $properties->symbol;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }
}