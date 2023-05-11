<?php

declare(strict_types=1);

namespace App\Domain;

use Core\Domain\{ReadRepositoryInterface, WriteRepositoryInterface};

abstract class AbstractOrderRepository implements ReadRepositoryInterface, WriteRepositoryInterface
{
    abstract public function findById(string $id): ?Order;
}
