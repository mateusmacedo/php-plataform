<?php

declare(strict_types=1);

namespace App\Application\Queries;
use Core\Application\QueryInterface;

final class FindProductsByIdsQuery implements QueryInterface
{
    public function __construct(public readonly array $productIds)
    {
    }
}