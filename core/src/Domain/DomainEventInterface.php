<?php

declare(strict_types=1);

namespace Core\Domain;

/**
 * Interface DomainEventInterface represents a domain event interface.
 *
 * @package Core\Domain
 * @author Mateus Macedo Dos Anjos <macedodosanjosmateus@gmail.com>
 * @version 0.0.1
 */
interface DomainEventInterface
{
    /**
     * Get the event occurred date.
     *
     * @return string
     */
    public function occurredOn(): string;
}
