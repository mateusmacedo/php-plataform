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
        public readonly ?string $id = null,
        public readonly ?string $createdAt = null,
        public readonly ?string $updatedAt = null,
        public readonly ?string $deletedAt = null
    ) {
    }
}