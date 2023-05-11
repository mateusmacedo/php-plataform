<?php

declare(strict_types=1);

namespace App\Domain;

use Core\Application\EventInterface;
use Core\Domain\AbstractDomainEvent;
use DateTimeImmutable;

final class ProductCreated extends AbstractDomainEvent implements EventInterface
{
    public function __construct(
        string $productId,
    ) {
        parent::__construct(
            eventType: 'product_created',
            eventId: uniqid(),
            occurredOn: (new DateTimeImmutable())->format(DateTimeImmutable::ISO8601),
            aggregateId: $productId
        );
    }
}
