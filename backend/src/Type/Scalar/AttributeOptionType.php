<?php declare(strict_types=1);

namespace App\Type\Scalar;

use App\Type\TypeRegistry;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class AttributeOptionType extends ObjectType {
    public function __construct()
    {
        parent::__construct([
            'name' => 'AttributeOptionType',
            'description' => 'Type of attribute',
            'fields' => static fn(): array => [
                'id' => [
                    'type' => Type::id(),
                    'resolve' => function ($root) {
                        return $root['id'];
                    }
                ],
                'attribute_option_id' => [
                    'type' => Type::int(),
                    'resolve' => function ($root) {
                        return $root['attribute_option_id'];
                    }
                ],
                'attribute_type_id' => [
                    'type' => Type::int(),
                    'resolve' => function ($root) {
                        return $root['attribute_type_id'];
                    }
                ],
                'attribute_option_value' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root['attribute_option_value'];
                    }
                ],
                'display_value' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root['display_value'];
                    }
                ],
            ],
            'interfaces' => [TypeRegistry::type(NodeType::class)],
        ]);
    }
}