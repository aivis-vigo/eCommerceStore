<?php declare(strict_types=1);

namespace App\Type\Scalar;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

class CartItemType extends InputObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'CartItem',
            'description' => 'Input type for items in the cart.',
            'fields' => [
                'productId' => [
                    'type' => Type::nonNull(Type::string()),
                ],
                'quantity' => [
                    'type' => Type::nonNull(Type::int()),
                ],
                'price' => [
                    'type' => Type::nonNull(Type::int()),
                ],
            ],
        ]);
    }
}
