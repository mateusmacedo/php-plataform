<?php

declare(strict_types=1);

namespace Core\Domain;

use Generator;
use SplObjectStorage;

/**
 * Class AbstractAggregateRoot represents an abstract aggregate root is base class for all aggregate roots.
 *
 * @author Mateus Macedo Dos Anjos <macedodosanjosmateus@gmail.com>
 *
 * @version 0.0.1
 */
abstract class AbstractAggregateRoot extends AbstractEntity
{
    protected SplObjectStorage $events;

    /**
     * AbstractAggregateRoot constructor is responsible for construct a new abstract aggregate root.
     *
     * @param null|string $id
     * @param null|string $createdAt
     * @param null|string $updatedAt
     * @param null|string $deletedAt
     */
    public function __construct(
        ?string $id = null,
        ?string $createdAt = null,
        ?string $updatedAt = null,
        ?string $deletedAt = null
    ) {
        parent::__construct($id, $createdAt, $updatedAt, $deletedAt);
        $this->clearEvents();
    }

    /**
     * Method to add a event to the aggregate root.
     *
     * @param AbstractDomainEvent $event
     */
    public function record(AbstractDomainEvent $event): void
    {
        $this->events->attach($event);
    }

    /**
     * Method to get all events of the aggregate root.
     *
     * @return Generator
     */
    public function getEvents(): Generator
    {
        foreach ($this->events as $event) {
            yield $event;
        }
    }

    /**
     * Method to clear all events of the aggregate root.
     */
    public function clearEvents(): void
    {
        $this->events = new SplObjectStorage();
    }
}
