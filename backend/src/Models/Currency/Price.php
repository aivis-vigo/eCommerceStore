<?php declare(strict_types=1);

namespace App\Models\Currency;

class Price
{
    private float $amount;
    private Currency $currency;

    public function __construct(object $properties)
    {
        $this->amount = $properties->amount;
        $this->currency = new Currency($properties->currency);
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }
}