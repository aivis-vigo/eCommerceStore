<?php declare(strict_types=1);

namespace App\Interfaces;

interface Product
{
    public function getId(): string;

    public function getName(): string;

    public function isAvailable(): bool;

    public function getPictures(): array;

    public function getDescription(): string;

    public function getCategory(): string;

    public function getPrices(): array;

    public function getBrand(): string;
}