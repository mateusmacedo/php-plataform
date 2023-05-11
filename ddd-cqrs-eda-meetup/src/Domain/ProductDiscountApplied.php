<?php

declare(strict_types=1);

namespace App\Domain;

use Core\Domain\AbstractDomainEvent;
use DateTimeImmutable;

final class ProductDiscountApplied extends AbstractDomainEvent
{
    public function __construct(
        public readonly float $discount,
        string $productId
    ) {
        parent::__construct(
            eventType: 'product_discount_applied',
            eventId: uniqid(),
            occurredOn: (new DateTimeImmutable())->format(DateTimeImmutable::ISO8601),
            aggregateId: $productId
        );
    }
}
