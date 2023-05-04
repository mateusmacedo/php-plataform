<?php

declare(strict_types=1);

namespace Core\Domain;

abstract class AbstractDomainEvent
{
    public function __construct(
        public readonly string $eventType,
        public readonly string $eventId,
        public readonly string $occurredOn,
        public readonly string $aggregateId
    ) {
    }
}
