<?php declare(strict_types=1);

namespace App\Type\Scalar;

use App\Type\TypeRegistry;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class ImageType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Image',
            'description' => 'Product colors',
            'fields' => static fn(): array => [
                'image_id' => [
                    'type' => Type::int(),
                    'resolve' => function ($root) {
                        return $root['image_id'];
                    }
                ],
                'product_item_id' => [
                    'type' => Type::int(),
                    'resolve' => function ($root) {
                        return $root['product_item_id'];
                    }
                ],
                'image_url' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root['image_url'];
                    }
                ],
            ],
            'interfaces' => [TypeRegistry::type(NodeType::class)],
        ]);
    }
}