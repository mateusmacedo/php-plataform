<?php

declare(strict_types=1);

namespace App\Infraestructure\Database;

use App\Domain\Order;
use App\Domain\OrderRepositoryInterface;

final class InMemoryOrderRepository implements OrderRepositoryInterface
{
    private array $orders = [];

    public function save(Order $order): void
    {
        $this->orders[$order->getId()] = $order;
    }

    public function findById(string $id): ?Order
    {
        return $this->orders[$id] ?? null;
    }
}