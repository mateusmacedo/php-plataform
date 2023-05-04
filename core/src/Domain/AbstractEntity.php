<?php

declare(strict_types=1);

namespace Core\Domain;

/**
 * Class AbstractEntity represents an abstract entity is base class for all entities.
 *
 * @package Core\Domain
 * @author Mateus Macedo Dos Anjos <macedodosanjosmateus@gmail.com>
 * @version 0.0.1
 */
abstract class AbstractEntity
{
    /**
     * AbstractEntity constructor is responsible for construct a new abstract entity.
     *
     * @param string|null $id
     * @param string|null $createdAt
     * @param string|null $updatedAt
     * @param string|null $deletedAt
     */
    public function __construct(
        protected ?string $id = null,
        protected ?string $createdAt = null,
        protected ?string $updatedAt = null,
        protected ?string $deletedAt = null
    ) {
    }

    /**
     * Method to get the id of the entity
     *
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }
}
