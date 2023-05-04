<?php

declare(strict_types=1);

namespace App\Domain;

use Core\Domain\AbstractDomainEvent;

final class ProductAddedToOrder extends AbstractDomainEvent
{
    public function __construct(public readonly string $productId, string $orderId)
    {
        parent::__construct(
            eventType: 'product_added_to_order',
            eventId: uniqid(),
            occurredOn: (new \DateTimeImmutable())->format(\DateTimeImmutable::ISO8601),
            aggregateId: $orderId
        );
    }
}