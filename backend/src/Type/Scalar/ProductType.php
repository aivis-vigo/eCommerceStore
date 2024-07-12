<?php declare(strict_types=1);

namespace App\Type\Scalar;

use App\Services\BrandService;
use App\Services\ImageService;
use App\Type\TypeRegistry;
use GraphQL\Type\Definition\ListOfType;
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
                'in_stock' => [
                    'type' => Type::int(),
                    'resolve' => function ($root) {
                        return $root['in_stock'];
                    }
                ],
                'original_price' => [
                    'type' => Type::float(),
                    'resolve' => function ($root) {
                        // returned as float so there's no need to do it in frontend
                        return $root['original_price'] / 100;
                    }
                ],
                'images' => [
                    'type' => Type::listOf(TypeRegistry::type(ImageType::class)),
                    'resolve' => function ($root) {
                        return (new ImageService())->findOneById($root['product_id']);
                    }
                ],
                'attributes' => [
                    'type' => Type::listOf(TypeRegistry::type(AttributeType::class)),
                    'resolve' => function ($root) {
                        $productAttributes = (new \App\Services\ProductAttributeService())->findOneById($root['product_id']);
                        $ids = [];
                        foreach ($productAttributes as $productAttribute) {
                            $ids[] = $productAttribute['attribute_option_id'];
                        }

                        $options = [];
                        foreach ($ids as $id) {
                            $options[] = (new \App\Services\AttributeOptionService())->findOneById($id);
                        }

                        $attributes = [];
                        foreach ($options as $option) {
                            $test = (new \App\Services\AttributeTypeService())->findOneById($option['attribute_type_id']);
                            if (!in_array($test, $attributes)) {
                                $attributes[] = $test;
                            }
                        }

                        return $attributes;
                    }
                ]
            ],
            'interfaces' => [TypeRegistry::type(NodeType::class)],
        ]);
    }
}