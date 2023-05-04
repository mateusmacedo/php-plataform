<?php

declare(strict_types=1);

namespace Core\Application\Handlers;

/**
 * Interface ChainInterface defines the contract for a chain of responsibility
 *
 * @package Core\Application
 * @author Mateus Macedo Dos Anjos <macedodosanjosmateus@gmail.com>
 * @since 0.0.1
 * @version 0.0.1
 */
interface ChainHandlerInterface extends HandlerInterface
{
    /**
     * Define the next handler in the chain
     *
     * @param HandlerInterface $handler
     * @return HandlerInterface
     */
    public function setNext(HandlerInterface $handler): HandlerInterface;
}
