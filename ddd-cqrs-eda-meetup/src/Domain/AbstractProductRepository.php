<?php

declare(strict_types=1);

namespace App\Domain;

use Core\Domain\{ReadRepositoryInterface, WriteRepositoryInterface};

abstract class AbstractProductRepository implements ReadRepositoryInterface, WriteRepositoryInterface
{
    abstract public function findById(string $id): ?Product;

    abstract public function findByIds(array $ids): array;
}
