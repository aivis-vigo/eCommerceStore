<?php declare(strict_types=1);

namespace App\Type\Scalar;

use App\Type\TypeRegistry;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class SizeOptionType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'SizeOption',
            'description' => 'Size option',
            'fields' => static fn(): array => [
                'id' => [
                    'type' => Type::id(),
                    'resolve' => function ($root) {
                        return $root['id'];
                    }
                ],
                'size_id' => [
                    'type' => Type::int(),
                    'resolve' => function ($root) {
                        return $root['size_id'];
                    }
                ],
                'size_display_value' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root['size_display_value'];
                    }
                ],
                'size_code' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root['size_code'];
                    }
                ],
                'size_category_id' => [
                    'type' => Type::int(),
                    'resolve' => function ($root) {
                        return $root['size_category_id'];
                    }
                ]
            ],
            'interfaces' => [TypeRegistry::type(NodeType::class)],
        ]);
    }
}