<?php

declare(strict_types=1);

namespace Core\Domain;

/**
 * Interface ReadRepositoryInterface represents a read repository interface.
 *
 * @package Core\Domain
 * @author Mateus Macedo Dos Anjos <macedodosanjosmateus@gmail.com>
 * @version 0.0.1
 */
interface ReadRepositoryInterface
{
    /**
     * Find an aggregate root by id.
     *
     * @param string $id
     * @return AbstractAggregateRoot|null
     */
    public function find(string $id): ?AbstractAggregateRoot;
}
