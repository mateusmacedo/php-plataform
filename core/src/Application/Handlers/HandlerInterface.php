<?php

declare(strict_types=1);

namespace Core\Application\Handlers;

use Core\Application\CommandInterface;
use Core\Application\EventInterface;
use Core\Application\QueryInterface;
use Core\Application\Result;

/**
 * Interface HandlerInterface defines the contract for a handler.
 *
 * @author Mateus Macedo Dos Anjos <macedodosanjosmateus@gmail.com>
 *
 * @version 0.0.1
 */
interface HandlerInterface
{
    /**
     * Handle a message and return a result.
     *
     * @param CommandInterface|QueryInterface|EventInterface $message
     *
     * @return Result
     */
    public function handle(CommandInterface|QueryInterface|EventInterface $message): Result;
}
