<?php declare(strict_types=1);

namespace App\Type\Scalar;

use App\Type\TypeRegistry;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class ProductVariationType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'ProductVariation',
            'description' => 'Different variations of product',
            'fields' => static fn(): array => [
                'variation_id' => [
                    'type' => Type::int(),
                    'resolve' => function ($root) {
                        return $root['variation_id'];
                    }
                ],
                'product_item_id' => [
                    'type' => Type::int(),
                    'resolve' => function ($root) {
                        return $root['product_item_id'];
                    }
                ],
                'size_id' => [
                    'type' => Type::int(),
                    'resolve' => function ($root) {
                        return $root['size_id'];
                    }
                ],
                'in_stock' => [
                    'type' => Type::int(),
                    'resolve' => function ($root) {
                        return $root['in_stock'];
                    }
                ],
            ],
            'interfaces' => [TypeRegistry::type(NodeType::class)],
        ]);
    }
}