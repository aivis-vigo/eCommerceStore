<?php declare(strict_types=1);

namespace App\Type\Scalar;

use App\Type\TypeRegistry;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class BrandType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Brand',
            'description' => 'Item brand info',
            'fields' => static fn(): array => [
                'brand_id' => [
                    'type' => Type::int(),
                    'resolve' => function ($root) {
                        return $root['brand_id'];
                    }
                ],
                'brand_name' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root['brand_name'];
                    }
                ],
            ],
            'interfaces' => [TypeRegistry::type(NodeType::class)],
        ]);
    }
}