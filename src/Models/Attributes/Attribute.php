<?php declare(strict_types=1);

namespace App\Models\Attributes;

class Attribute
{
    private string $id, $name, $type;
    private array $options;

    public function __construct(object $properties)
    {
        $this->id = $properties->id;
        $this->options = $this->createOptions($properties->items);
        $this->name = $properties->name;
        $this->type = $properties->type;
    }

    public function createOptions(array $options): array
    {
        $sizes = [];

        foreach ($options as $item) {
            $size = new Option($item);

            $sizes[] = $size;
        }

        return $sizes;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }
}