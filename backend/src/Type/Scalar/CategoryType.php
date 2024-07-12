<?php declare(strict_types=1);

namespace App\Type\Scalar;

use App\Type\TypeRegistry;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class CategoryType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Category',
            'description' => 'Category for products',
            'fields' => static fn(): array => [
                'product_category_id' => [
                    'type' => Type::int(),
                    'resolve' => function ($root) {
                        return $root['product_category_id'];
                    }
                ],
                'parent_category_id' => [
                    'type' => Type::int(),
                    'resolve' => function ($root) {
                        return $root['parent_category_id'];
                    }
                ],
                'size_category_id' => [
                    'type' => Type::int(),
                    'resolve' => function ($root) {
                        return $root['size_category_id'];
                    }
                ],
                'category_name' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root['category_name'];
                    }
                ],
            ],
            'interfaces' => [TypeRegistry::type(NodeType::class)],
        ]);
    }
}