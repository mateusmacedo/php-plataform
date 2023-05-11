<?php

declare(strict_types=1);

namespace Core\Application;

interface MessageDispatcherInterface
{
    /**
     * Dispatch a message.
     *
     * @param CommandInterface|QueryInterface|EventInterface $message
     */
    public function dispatch(CommandInterface|QueryInterface|EventInterface $message, array $metadata = []): void;
}
