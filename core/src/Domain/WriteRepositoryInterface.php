<?php

declare(strict_types=1);

namespace Core\Domain;

/**
 * Interface WriteRepositoryInterface represents a write repository interface.
 *
 * @author Mateus Macedo Dos Anjos <macedodosanjosmateus@gmail.com>
 *
 * @version 0.0.1
 */
interface WriteRepositoryInterface
{
    public function save($aggregateRoot);
}
