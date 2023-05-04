<?php

declare(strict_types=1);

namespace App\Application\Commands;

use App\Domain\Product;
use Core\Application\CommandInterface;

final class CreateProductCommand implements CommandInterface
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $description,
        public readonly float $price
    ) {
    }
}