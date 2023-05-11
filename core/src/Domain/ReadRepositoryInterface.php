<?php

declare(strict_types=1);

namespace Core\Domain;

/**
 * Interface ReadRepositoryInterface represents a read repository interface.
 *
 * @author Mateus Macedo Dos Anjos <macedodosanjosmateus@gmail.com>
 *
 * @version 0.0.1
 */
interface ReadRepositoryInterface
{
    public function find($id);
}
