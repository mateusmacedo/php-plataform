<?php

declare(strict_types=1);

namespace Core\Infrastructure\Database;

use ArrayObject;

class ListResponse
{
    private function __construct(
        public readonly array|ArrayObject $rows,
        public readonly int $perPage,
        public readonly int $totalRows
    ) {
    }

    public static function create(array|ArrayObject $rows, int $perPage, int $totalRows): self
    {
        return new self($rows, $perPage, $totalRows);
    }
}
