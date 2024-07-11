<?php declare(strict_types=1);

namespace App\Type;

use Closure;

final class TypeRegistry
{
    private static array $types = [];

    public static function type(string $className): Closure
    {
        return static fn() => self::$types[$className] ??= new $className();
    }
}