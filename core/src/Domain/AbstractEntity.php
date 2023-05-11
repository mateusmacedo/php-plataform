<?php

declare(strict_types=1);

namespace Core\Domain;
use DateTimeImmutable;

/**
 * Class AbstractEntity represents an abstract entity is base class for all entities.
 *
 * @author Mateus Macedo Dos Anjos <macedodosanjosmateus@gmail.com>
 *
 * @version 0.0.1
 */
abstract class AbstractEntity
{
    /**
     * AbstractEntity constructor is responsible for construct a new abstract entity.
     *
     * @param null|string $id
     * @param null|string $createdAt
     * @param null|string $updatedAt
     * @param null|string $deletedAt
     */
    public function __construct(
        public readonly ?string $id = null,
        public readonly ?string $createdAt = null,
        protected ?string $updatedAt = null,
        protected ?string $deletedAt = null
    ) {
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    public function getDeletedAt(): ?string
    {
        return $this->deletedAt;
    }

    public function itsDeleted(): bool
    {
        return $this->deletedAt !== null;
    }
}
