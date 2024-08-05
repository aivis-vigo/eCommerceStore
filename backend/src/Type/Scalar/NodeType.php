<?php declare(strict_types=1);

namespace App\Type\Scalar;

use App\Models\Attributes\Attribute;
use App\Type\TypeRegistry;
use GraphQL\Type\Definition\InterfaceType;
use GraphQL\Type\Definition\Type;
use GraphQL\Utils\Utils;

class NodeType extends InterfaceType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Node',
            'fields' => [
                'id' => Type::id(),
            ],
            'resolveType' => [$this, 'resolveNodeType'],
        ]);
    }

    /**
     * @param mixed $object
     *
     * @return callable(): ObjectType
     * @throws \Exception
     *
     */
    public function resolveNodeType($object)
    {
        if ($object instanceof ProductType) {
            return TypeRegistry::type(ProductType::class);
        }

        if ($object instanceof CategoryType) {
            return TypeRegistry::type(CategoryType::class);
        }

        if ($object instanceof BrandType) {
            return TypeRegistry::type(BrandType::class);
        }

        if ($object instanceof ProductItemType) {
            return TypeRegistry::type(ProductItemType::class);
        }

        if ($object instanceof ColorType) {
            return TypeRegistry::type(CategoryType::class);
        }

        if ($object instanceof ImageType) {
            return TypeRegistry::type(ImageType::class);
        }

        if ($object instanceof ProductVariationType) {
            return TypeRegistry::type(ProductVariationType::class);
        }

        if ($object instanceof AttributeType) {
            return TypeRegistry::type(AttributeType::class);
        }

        if ($object instanceof AttributeOptionType) {
            return TypeRegistry::type(AttributeOptionType::class);
        }

        if ($object instanceof ProductAttributeType) {
            return TypeRegistry::type(ProductAttributeType::class);
        }

        if ($object instanceof Attribute) {
            return TypeRegistry::type(Attribute::class);
        }

        if ($object instanceof SizeOptionType) {
            return TypeRegistry::type(SizeOptionType::class);
        }

        if ($object instanceof CartItemType) {
            return TypeRegistry::type(CartItemType::class);
        }

        $notNode = Utils::printSafe($object);
        throw new \Exception("Unknown type: {$notNode}");
    }
}