<?php

declare(strict_types=1);

namespace App\Domain;

use Core\Domain\AbstractDomainEvent;

final class OrderIssued extends AbstractDomainEvent
{
    public function __construct(string $orderId)
    {
        parent::__construct(
            eventType: 'order_issued',
            eventId: uniqid(),
            occurredOn: (new \DateTimeImmutable())->format(\DateTimeImmutable::ISO8601),
            aggregateId: $orderId
        );
    }
}