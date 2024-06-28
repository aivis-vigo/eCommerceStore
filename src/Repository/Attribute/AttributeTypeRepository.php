<?php declare(strict_types=1);

namespace App\Repository\Attribute;

use App\Database\Database;
use App\Interfaces\Product;
use Doctrine\DBAL\Query\QueryBuilder;

// todo: CRUD operations

class AttributeTypeRepository
{
    private Database $db;
    private QueryBuilder $queryBuilder;

    public function __construct(Database $db)
    {
        $this->db = $db;
        $this->queryBuilder = $this->db->createQueryBuilder();
    }

    public function insertOne(Product $properties): void
    {
        $attributes = $properties->getAttributeArray();

        foreach ($attributes as $attribute) {
            $className = $this->getClassName($attribute);

            if (!$this->checkIfExists($className)) {
                $typeId = $this->getTypeId();

                $this->db->createQueryBuilder()
                    ->insert('attribute_type')
                    ->values([
                        'attribute_type_id' => ':attribute_type_id',
                        'attribute_name' => ':attribute_name'
                    ])
                    ->setParameters([
                        'attribute_type_id' => $typeId,
                        'attribute_name' => $className
                    ])
                    ->executeStatement();
            }
        }
    }

    public function checkIfExists(string $name): bool
    {
        $colorName = $this->db->createQueryBuilder()
            ->select('attribute_type_id')
            ->from('attribute_type')
            ->where('attribute_name = :attribute_name')
            ->setParameter('attribute_name', $name)
            ->executeQuery()
            ->fetchOne();

        if ($colorName) {
            return true;
        }

        return false;
    }

    public function getTypeId(): int
    {
        $typeId = $this->queryBuilder
            ->select('attribute_type_id')
            ->from('attribute_type')
            ->orderBy('attribute_type_id', 'DESC')
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchOne();

        if (!$typeId) {
            return 1;
        }

        return $typeId + 1;
    }

    public function getClassName(object $properties): string
    {
        $fullClassName = get_class($properties);
        return basename(str_replace('\\', '/', $fullClassName));
    }
}