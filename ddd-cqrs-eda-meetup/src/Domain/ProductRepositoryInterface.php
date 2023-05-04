<?php

declare(strict_types=1);

namespace App\Domain;

use Core\Domain\ReadRepositoryInterface;
use Core\Domain\WriteRepositoryInterface;

interface ProductRepositoryInterface extends ReadRepositoryInterface, WriteRepositoryInterface
{
    public function findById(string $id): ?Product;

    public function findByIds(array $ids): array;
}