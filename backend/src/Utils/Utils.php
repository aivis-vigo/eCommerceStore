<?php declare(strict_types=1);

namespace App\Utils;

class ClassUtils
{
    public static function getClassName(object $properties): string
    {
        $fullClassName = get_class($properties);
        return basename(str_replace('\\', '/', $fullClassName));
    }

    public static function formatPrice(float $price): int
    {
        return (int)($price * 100);
    }
}
