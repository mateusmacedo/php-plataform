<?php

declare(strict_types=1);

namespace App\Infraestructure\Message;

use Core\Application\MessageDispatcherInterface;

final class InMemoryMessageDispatcher implements MessageDispatcherInterface
{
    private array $handlers = [];

    public function dispatch(object $message): void
    {
        $this->handlers[get_class($message)]($message);
    }

    public function addHandler(string $messageClass, callable $handler): void
    {
        $this->handlers[$messageClass] = $handler;
    }
}