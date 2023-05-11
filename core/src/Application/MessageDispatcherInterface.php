<?php

declare(strict_types=1);

namespace Core\Application;

interface MessageDispatcherInterface
{
    /**
     * Dispatch a message.
     *
     * @param MessageInterface $message
     */
    public function dispatch(MessageInterface $message, array $metadata = []): void;
}
