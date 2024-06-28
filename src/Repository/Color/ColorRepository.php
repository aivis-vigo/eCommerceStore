<?php declare(strict_types=1);

namespace App\Repository\Color;

use App\Database\Database;
use App\Models\Attributes\Option;
use Doctrine\DBAL\Query\QueryBuilder;

// todo: CRUD operations

class ColorRepository
{
    private Database $db;
    private QueryBuilder $queryBuilder;

    public function __construct(Database $db)
    {
        $this->db = $db;
        $this->queryBuilder = $this->db->createQueryBuilder();
    }

    public function insertOne(Option $properties): void
    {
        if (!$this->checkIfExists($properties->getDisplayValue())) {
            $colorId = $this->getColorId();
            $colorName = $properties->getDisplayValue();
            $hexValue = $properties->getValue();

            $this->db->createQueryBuilder()
                ->insert('color')
                ->values([
                    'color_id' => ':color_id',
                    'color_name' => ':color_name',
                    'color_hex_value' => ':color_hex_value'
                ])
                ->setParameter('color_id', $colorId)
                ->setParameter('color_name', $colorName)
                ->setParameter('color_hex_value', $hexValue)
                ->executeStatement();
        }
    }

    public function checkIfExists(string $name): bool
    {
        $colorName = $this->db->createQueryBuilder()
            ->select('color_name')
            ->from('color')
            ->where('color_name = :name')
            ->setParameter('name', $name)
            ->executeQuery()
            ->fetchOne();

        if ($colorName) {
            return true;
        }

        return false;
    }

    public function getColorId(): int
    {
        $colorId = $this->queryBuilder
            ->select('color_id')
            ->from('color')
            ->orderBy('color_id', 'DESC')
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchOne();

        if (!$colorId) {
            return 1;
        }

        return $colorId + 1;
    }
}