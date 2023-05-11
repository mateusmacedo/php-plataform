<?php

declare(strict_types=1);

namespace App\Infraestructure\Message;

use Core\Application\MessageDispatcherInterface;

final class InMemoryMessageDispatcher implements MessageDispatcherInterface
{
    private array $handlers = [];

    public function dispatch(object $message, ?array $metadata = []): void
    {
        if (isset($this->handlers[get_class($message)])) {
            foreach ($this->handlers[get_class($message)] as $handler) {
                $handler($message, $metadata);
            }
        }
    }

    public function addHandler(string $messageClass, callable $handler): void
    {
        $this->handlers[$messageClass][] = $handler;
    }
}
