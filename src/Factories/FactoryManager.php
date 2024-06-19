<?php declare(strict_types=1);

namespace App\Factories;

use App\Models\Factories\FashionProductFactory;
use App\Models\Factories\TechProductFactory;
use InvalidArgumentException;

class FactoryManager
{
    private array $activeFactories = [];
    private TechProductFactory $techProductFactory;
    private FashionProductFactory $fashionProductFactory;

    public function __construct()
    {
        $this->techProductFactory = new TechProductFactory();
        $this->fashionProductFactory = new FashionProductFactory();
    }

    public function getFactory(string $category)
    {
        if (isset($this->activeFactories[$category])) {
            return $this->activeFactories[$category];
        }

        switch ($category) {
            case 'tech':
                $this->activeFactories[$category] = $this->techProductFactory;
                return $this->activeFactories[$category];
            case 'clothes':
                $this->activeFactories[$category] = $this->fashionProductFactory;
                return $this->activeFactories[$category];
            default:
                return new InvalidArgumentException("Unknown category: $category");
        }
    }
}