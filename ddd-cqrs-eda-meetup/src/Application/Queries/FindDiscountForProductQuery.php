<?php

declare(strict_types=1);

namespace App\Application;
use Core\Application\QueryInterface;


final class FindDiscountForProductQuery implements QueryInterface
{
    public function __construct(
        public readonly string $productId,
        public readonly string $discountCode
    ) {
    }
}