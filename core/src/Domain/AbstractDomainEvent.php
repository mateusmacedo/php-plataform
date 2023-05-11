<?php

declare(strict_types=1);

namespace Core\Domain;
use Core\Application\EventInterface;

abstract class AbstractDomainEvent implements EventInterface
{
    public function __construct(
        public readonly string $eventType,
        public readonly string $eventId,
        public readonly string $occurredOn,
        public readonly string $aggregateId
    ) {
    }
}
