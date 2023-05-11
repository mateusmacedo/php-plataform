<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Stubs;

use Core\Domain\AbstractAggregateRoot;

final class AggregateRootStub extends AbstractAggregateRoot
{
    public function __construct(
        ?string $id = null,
        ?string $createdAt = null,
        ?string $updatedAt = null,
        ?string $deletedAt = null
    ) {
        parent::__construct($id, $createdAt, $updatedAt, $deletedAt);
    }

    public function getId(): string
    {
        return $this->id;
    }
}
