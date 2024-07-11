<?php declare(strict_types=1);

namespace App\Type\Scalar;

use App\Type\TypeRegistry;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class ColorType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Color',
            'description' => 'Product colors',
            'fields' => static fn(): array => [
                'color_id' => [
                    'type' => Type::int(),
                    'resolve' => function ($root) {
                        return $root['color_id'];
                    }
                ],
                'color_name' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root['color_name'];
                    }
                ],
                'color_hex_value' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root['color_hex_value'];
                    }
                ],
            ],
            'interfaces' => [TypeRegistry::type(NodeType::class)],
        ]);
    }
}