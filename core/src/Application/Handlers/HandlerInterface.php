<?php

declare(strict_types=1);

namespace Core\Application;

/**
 * Interface HandlerInterface defines the contract for a handler
 *
 * @package Core\Application
 * @author Mateus Macedo Dos Anjos <macedodosanjosmateus@gmail.com>
 * @version 0.0.1
 */
interface HandlerInterface
{
    /**
     * Handle a message and return a result
     *
     * @param MessageInterface $message
     * @return Result
     */
    public function handle(MessageInterface $message): Result;
}
