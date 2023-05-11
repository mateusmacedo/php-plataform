<?php

declare(strict_types=1);

namespace Core\Application\Handlers;

use Core\Application\MessageInterface;
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
     * @param MessageInterface $message
     *
     * @return Result
     */
    public function handle(MessageInterface $message): Result;
}
