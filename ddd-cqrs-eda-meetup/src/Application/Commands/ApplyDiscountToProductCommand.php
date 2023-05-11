<?php

declare(strict_types=1);

namespace App\Application\Commands;

use Core\Application\CommandInterface;

final class ApplyDiscountToProductCommand implements CommandInterface
{
    public function __construct(
        public readonly float $discount,
        public readonly string $productId
    ) {
    }
}
