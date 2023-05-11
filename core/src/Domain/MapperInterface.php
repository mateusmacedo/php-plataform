<?php

declare(strict_types=1);

namespace Core\Shared;

interface MapperInterface
{
    public function toDomain($rawData): mixed;

    public function toDto($data, ?string $convertTo): mixed;

    public function toPersistence($domainData): ?array;
}
