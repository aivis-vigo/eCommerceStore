<?php declare(strict_types=1);

namespace App\Repositories\Attribute;

use App\Repositories\BaseRepository;

class AttributeOptionRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    public function findAll(): array
    {
        return $this->createQueryBuilder()
            ->select('*')
            ->from('attribute_option')
            ->executeQuery()
            ->fetchAllAssociative();
    }

    public function findOneById(int $attribute_option_id): array
    {
        return $this->createQueryBuilder()
            ->select('*')
            ->from('attribute_option')
            ->where('attribute_option_id = :attribute_option_id')
            ->setParameter('attribute_option_id', $attribute_option_id)
            ->executeQuery()
            ->fetchAssociative();
    }

    public function findAllById(int $attribute_type_id): array
    {
        return $this->createQueryBuilder()
            ->select('*')
            ->from('attribute_option')
            ->where('attribute_type_id = :attribute_type_id')
            ->setParameter('attribute_type_id', $attribute_type_id)
            ->executeQuery()
            ->fetchAllAssociative();
    }

    public function insertOne(array $types): void
    {
        if ($types) {
            foreach ($types as $type) {
                $options = $type->getOptions();
                $className = $this->getClassName($type);
                $typeId = $this->getAttributeTypeId($className);

                foreach ($options as $option) {
                    $value = $option->getValue();
                    $displayValue = $option->getDisplayValue();

                    if (!$this->checkIfExists($typeId, $value)) {
                        $optionId = $this->getAttributeOptionId();
                        $value = $option->getValue();

                        $this->createQueryBuilder()
                            ->insert('attribute_option')
                            ->values([
                                'attribute_option_id' => ':attribute_option_id',
                                'attribute_type_id' => ':attribute_type_id',
                                'attribute_option_value' => ':attribute_option_value',
                                'display_value' => ':display_value',
                            ])
                            ->setParameters([
                                'attribute_option_id' => $optionId,
                                'attribute_type_id' => $typeId,
                                'attribute_option_value' => $value,
                                'display_value' => $displayValue,
                            ])
                            ->executeStatement();
                    }
                }
            }
        }
    }

    public function checkIfExists(int $typeId, string $value): bool
    {
        $option = $this->createQueryBuilder()
            ->select('attribute_option_id')
            ->from('attribute_option')
            ->where('attribute_type_id = :attribute_type_id')
            ->andWhere('attribute_option_value = :value')
            ->setParameters([
                'attribute_type_id' => $typeId,
                'value' => $value
            ])
            ->executeQuery()
            ->fetchOne();

        if ($option) {
            return true;
        }

        return false;
    }

    public function getAttributeOptionId(): int
    {
        $attributeOptionId = $this->createQueryBuilder()
            ->select('attribute_option_id')
            ->from('attribute_option')
            ->orderBy('attribute_option_id', 'DESC')
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchOne();


        if (!$attributeOptionId) {
            return 1;
        }

        return $attributeOptionId + 1;
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

    public function getClassName(object $properties): string
    {
        $fullClassName = get_class($properties);
        return basename(str_replace('\\', '/', $fullClassName));
    }
}