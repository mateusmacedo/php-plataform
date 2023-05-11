<?php

declare(strict_types=1);

namespace App\Domain;

use Core\Application\EventInterface;
use Core\Domain\AbstractDomainEvent;
use DateTimeImmutable;

final class OrderFetched extends AbstractDomainEvent implements EventInterface
{
    public function __construct(string $orderId)
    {
        parent::__construct(
            eventType: 'order_fetched',
            eventId: uniqid(),
            occurredOn: (new DateTimeImmutable())->format(DateTimeImmutable::ISO8601),
            aggregateId: $orderId
        );
    }
}
