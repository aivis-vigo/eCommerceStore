<?php declare(strict_types=1);

namespace App\Type\Scalar;

use App\Type\TypeRegistry;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class ProductAttributeType extends ObjectType {
    public function __construct()
    {
        parent::__construct([
            'name' => 'AttributeType',
            'description' => 'Type of attribute',
            'fields' => static fn(): array => [
                'attribute_option_id' => [
                    'type' => Type::int(),
                    'resolve' => function ($root) {
                        return $root['attribute_option_id'];
                    }
                ],
                'product_id' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root['product_id'];
                    }
                ],
            ],
            'interfaces' => [TypeRegistry::type(NodeType::class)],
        ]);
    }
}