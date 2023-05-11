<?php

declare(strict_types=1);

namespace App\Infraestructure\Database;

use App\Domain\{AbstractOrderRepository, Order};

final class InMemoryOrderRepository extends AbstractOrderRepository
{
    private array $orders = [];

    /**
     * @param string $id
     *
     * @return null|Order
     */
    public function find($id): ?Order
    {
        return $this->orders[$id] ?? null;
    }

    /**
     * @param string $id
     *
     * @return null|Order
     */
    public function findById(string $id): ?Order
    {
        return $this->find($id);
    }

    /**
     * Save an aggregate root.
     *
     * @param Order $aggregateRoot
     */
    public function save($aggregateRoot): void
    {
        $this->orders[$aggregateRoot->id] = $aggregateRoot;
    }
}
