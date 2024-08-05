<?php declare(strict_types=1);

namespace App\Type\Scalar;

use App\Services\AttributeOptionService;
use App\Services\AttributeTypeService;
use App\Services\BrandService;
use App\Services\CategoryService;
use App\Services\ImageService;
use App\Services\ProductAttributeService;
use App\Services\ProductService;
use App\Services\ProductVariationService;
use App\Services\SizeCategoryService;
use App\Services\SizeOptionService;
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
                'id' => [
                    'type' => Type::id(),
                    'resolve' => function ($root) {
                        return $root['id'];
                    }
                ],
                'product_id' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root['product_id'];
                    }
                ],
                'category_name' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return (new ProductService())->findProductCategory($root['product_category_id']);
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
                        return $root['original_price'];
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
                        $productAttributes = (new ProductAttributeService())->findOneById($root['product_id']);
                        $ids = [];
                        foreach ($productAttributes as $productAttribute) {
                            $ids[] = $productAttribute['attribute_option_id'];
                        }

                        $options = [];
                        foreach ($ids as $id) {
                            $options[] = (new AttributeOptionService())->findOneById($id);
                        }

                        $attributes = [];
                        foreach ($options as $option) {
                            $test = (new AttributeTypeService())->findOneById($option['attribute_type_id']);
                            if (!in_array($test, $attributes)) {
                                $attributes[] = $test;
                            }
                        }
                        return $attributes;
                    }],
                'size_options' => [
                    'type' => Type::listOf(TypeRegistry::type(SizeOptionType::class)),
                    'resolve' => function ($root) {
                        if ($root['product_category_id'] === 2) {
                            if ($root['product_id'] == 'huarache-x-stussy-le') {
                                return (new SizeOptionService())->findAllByCategoryId(1);
                            }

                            return (new SizeOptionService())->findAllByCategoryId(2);
                        }

                        return  [];
                    }
                ],
            ],
            'interfaces' => [TypeRegistry::type(NodeType::class)],]);
    }
}