<?php

declare(strict_types=1);

namespace App\Domain;

interface OrderRepositoryInterface
{
    public function save(Order $order): void;
    public function findById(string $id): ?Order;
}