<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Stubs;

use Core\Domain\AbstractDomainEvent;

final class DomainEventStub extends AbstractDomainEvent
{
    public function __construct(
        string $eventType,
        string $eventId,
        string $occurredOn,
        string $aggregateId,
        public readonly string $dummyProperty = 'dummy-property'
    ) {
        parent::__construct($eventType, $eventId, $occurredOn, $aggregateId);
    }
}
