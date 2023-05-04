<?php

declare(strict_types=1);

namespace Core\Domain;

/**
 * Interface FactoryInterface
 *
 * @package Core\Domain
 * @author Mateus Macedo Dos Anjos <macedodosanjosmateus@gmail.com>
 * @version 0.0.1
 */
interface FactoryMethodInterface
{
    /**
     * Method to create a new instance of the class
     *
     * @param mixed $data
     * @return mixed
     */
    public static function create($data);
}
