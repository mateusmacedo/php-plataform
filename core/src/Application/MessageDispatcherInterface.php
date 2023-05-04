<?php

declare(strict_types=1);

namespace Core\Application;

interface MessageDispatcherInterface
{
    /**
     * Dispatch a message
     *
     * @param MessageInterface $message
     * @return void
     */
    public function dispatch(MessageInterface $message): void;
}
