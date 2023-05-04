<?php

declare(strict_types=1);

namespace App\Domain;

use Core\Domain\AbstractDomainEvent;
use DateTimeImmutable;

final class ProductRemovedFromOrder extends AbstractDomainEvent
{
    public function __construct(public readonly string $productId, string $orderId)
    {
        parent::__construct(
            eventType: 'product_removed_from_order',
            eventId: uniqid(),
            occurredOn: (new DateTimeImmutable())->format(DateTimeImmutable::ISO8601),
            aggregateId: $orderId
        );
    }
}