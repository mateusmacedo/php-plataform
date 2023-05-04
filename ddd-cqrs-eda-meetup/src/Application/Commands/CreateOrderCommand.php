<?php

declare(strict_types=1);

namespace App\Application;

use Core\Application\CommandInterface;

final class CreateOrderCommand implements CommandInterface
{
    public function __construct(public readonly string $id, public readonly array $products)
    {
    }
}