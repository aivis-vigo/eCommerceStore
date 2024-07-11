<?php declare(strict_types=1);

namespace App\Type\Scalar;

use App\Services\BrandService;
use App\Services\CategoryService;
use App\Services\ProductItemService;
use App\Type\TypeRegistry;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class ProductType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Product',
            'description' => 'Tech or Fashion products',
            'fields' => static fn(): array => [
                'product_id' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root['product_id'];
                    }
                ],
                'category' => [
                    'type' => TypeRegistry::type(CategoryType::class),
                    'resolve' => function ($root) {
                        return (new CategoryService())->findOneById($root['product_category_id']);
                    }
                ],
                'name' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root['name'];
                    }
                ],
                'description' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root['description'];
                    }
                ],
                'brand' => [
                    'type' => TypeRegistry::type(BrandType::class),
                    'resolve' => function ($root) {
                        return (new BrandService())->findOneById($root['brand_id']);
                    }
                ],
                'product_items' => [
                    'type' => Type::listOf(TypeRegistry::type(ProductItemType::class)),
                    'resolve' => function ($root) {
                        return (new ProductItemService())->findOneById($root['product_id']);
                    }
                ],
            ],
            'interfaces' => [TypeRegistry::type(NodeType::class)],
        ]);
    }
}