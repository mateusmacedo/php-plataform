<?php

declare(strict_types=1);

namespace App\Domain;

use Core\Domain\AbstractDomainEvent;
use DateTimeImmutable;

final class OrderCreated extends AbstractDomainEvent
{
    public function __construct(public readonly array $products, string $orderId)
    {
        parent::__construct(
            eventType: 'order_created',
            eventId: uniqid(),
            occurredOn: (new DateTimeImmutable())->format(DateTimeImmutable::ISO8601),
            aggregateId: $orderId
        );
    }
}
