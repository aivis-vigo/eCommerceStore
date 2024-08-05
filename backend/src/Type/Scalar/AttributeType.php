<?php declare(strict_types=1);

namespace App\Type\Scalar;

use App\Services\AttributeOptionService;
use App\Type\TypeRegistry;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class AttributeType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'AttributeType',
            'description' => 'Type of attribute',
            'fields' => static fn(): array => [
                'id' => [
                    'type' => Type::id(),
                    'resolve' => function ($root) {
                        return $root['id'];
                    }
                ],
                'attribute_type_id' => [
                    'type' => Type::int(),
                    'resolve' => function ($root) {
                        return $root['attribute_type_id'];
                    }
                ],
                'attribute_name' => [
                    'type' => Type::string(),
                    'resolve' => function ($root) {
                        return $root['attribute_name'];
                    }
                ],
                'attribute_options' => [
                    'type' => Type::listOf(TypeRegistry::type(AttributeOptionType::class)),
                    'resolve' => function ($root) {
                        return (new AttributeOptionService())->findAllById($root['attribute_type_id']);
                    }
                ]
            ],
            'interfaces' => [TypeRegistry::type(NodeType::class)],
        ]);
    }
}