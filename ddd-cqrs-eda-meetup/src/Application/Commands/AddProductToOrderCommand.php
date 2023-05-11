<?php

declare(strict_types=1);

namespace App\Application\Commands;

use Core\Application\CommandInterface;

final class AddProductToOrderCommand implements CommandInterface
{
    public function __construct(public readonly string $productId, public readonly string $orderId)
    {
    }
}
