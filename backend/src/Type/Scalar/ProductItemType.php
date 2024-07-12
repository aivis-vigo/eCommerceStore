<?php declare(strict_types=1);

namespace App\Type\Scalar;

use App\Services\AttributeOptionService;
use App\Services\AttributeTypeService;
use App\Services\ColorService;
use App\Services\ImageService;
use App\Services\ProductAttributeService;
use App\Services\ProductVariationService;
use App\Type\TypeRegistry;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class ProductItemType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'ProductItems',
            'description' => 'Variations of product',
            'fields' => static fn(): array => [
                'product_item_id' => [
                    'type' => Type::int(),
                    'resolve' => function ($root) {
                        return $root['product_item_id'];
                    }
                ],
                'product_id' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root['product_id'];
                    }
                ],
                'color_id' => [
                    'type' => Type::int(),
                    'resolve' => function ($root) {
                        return $root['color_id'];
                    }
                ],
                'original_price' => [
                    'type' => Type::int(),
                    'resolve' => function ($root) {
                        return $root['original_price'];
                    }
                ],
                'color' => [
                    'type' => TypeRegistry::type(ColorType::class),
                    'resolve' => function ($root) {
                        return (new ColorService())->findOneById($root['color_id']);
                    }
                ],
                'images' => [
                    'type' => Type::listOf(TypeRegistry::type(ImageType::class)),
                    'resolve' => function ($root) {
                        return (new ImageService())->findOneById($root['product_item_id']);
                    }
                ],
                'product_variation' => [
                    'type' => TypeRegistry::type(ProductVariationType::class),
                    'resolve' => function ($root) {
                        return (new ProductVariationService())->findOneById($root['product_item_id']);
                    }
                ],
                'product_attributes' => [
                    'type' => Type::listOf(TypeRegistry::type(AttributeType::class)),
                    'resolve' => function ($root) {
                        $ids = [];
                        $productAttributes = (new ProductAttributeService())->findOneById($root['product_id']);
                        foreach ($productAttributes as $productAttribute) {
                            $ids[] = $productAttribute["attribute_option_id"];
                        }

                        $results = [];
                        $seenTypes = [];

                        foreach ($ids as $attributeOptionId) {
                            $result = (new AttributeOptionService())->findOneById($attributeOptionId);
                            $type = (new AttributeTypeService())->findOneById($result["attribute_type_id"]);
                            if (!isset($seenTypes[$type['attribute_name']])) {
                                $results[] = $type;
                                $seenTypes[$type['attribute_name']] = true;
                            }
                        }

                        return $results;
                    }
                ]
            ],
            'interfaces' => [TypeRegistry::type(NodeType::class)],
        ]);
    }
}