<?php declare(strict_types=1);

namespace App\Repositories\Attribute;

use App\Interfaces\Product;
use App\Repositories\BaseRepository;

class ProductAttributeRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function findAll(): array
    {
        return $this->createQueryBuilder()
            ->select('*')
            ->from('product_attribute')
            ->executeQuery()
            ->fetchAssociative();
    }

    // todo: should be name findAllById
    public function findOneById(string $product_id): array
    {
        return $this->createQueryBuilder()
            ->select('*')
            ->from('product_attribute')
            ->where('product_id = :product_id')
            ->setParameter('product_id', $product_id)
            ->executeQuery()
            ->fetchAllAssociative();
    }

    public function insertOne(Product $properties): void
    {
        $attributes = $properties->getAttributeArray();
        $productName = $properties->getName();
        $productId = $this->getProductId($productName);

        foreach ($attributes as $attribute) {
            $className = $this->getClassName($attribute);
            $attributeTypeId = $this->getAttributeTypeId($className);
            $options = $attribute->getOptions();

            foreach ($options as $option) {
                $attributeOptionId = $this->getAttributeOptionId($attributeTypeId, $option->getValue());

                $this->createQueryBuilder()
                    ->insert('product_attribute')
                    ->values([
                        'product_id' => ':product_id',
                        'attribute_option_id' => ':attribute_option_id',
                    ])
                    ->setParameters([
                        'product_id' => $productId,
                        'attribute_option_id' => $attributeOptionId,
                    ])
                    ->executeStatement();
            }
        }
    }

    public function getProductId(string $name): string

    {
        return $this->createQueryBuilder()
            ->select('product_id')
            ->from('product')
            ->where('name = :name')
            ->setParameter('name', $name)
            ->executeQuery()
            ->fetchOne();
    }

    public function getAttributeTypeId(string $name): int
    {
        return $this->createQueryBuilder()
            ->select('attribute_type_id')
            ->from('attribute_type')
            ->where('attribute_name = :attribute_name')
            ->setParameter('attribute_name', $name)
            ->executeQuery()
            ->fetchOne();
    }

    public function getAttributeOptionId(int $attributeTypeId, string $name): int
    {
        return $this->createQueryBuilder()
            ->select('attribute_option_id')
            ->from('attribute_option')
            ->where('attribute_type_id = :attribute_type_id')
            ->andWhere('attribute_option_value = :attribute_option_value')
            ->setParameters([
                'attribute_type_id' => $attributeTypeId,
                'attribute_option_value' => $name,
            ])
            ->executeQuery()
            ->fetchOne();
    }

    // todo: Util function?
    public function getClassName(object $properties): string
    {
        $fullClassName = get_class($properties);
        return basename(str_replace('\\', '/', $fullClassName));
    }
}