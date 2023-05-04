<?php

declare(strict_types=1);

namespace Core\Application;

use Ds\Map;

class AbstractMessageFactory
{
    private Map $messages;

    public function __construct(string $actionsEnumPath)
    {
        $this->messages = new Map();
        $this->register($actionsEnumPath);
    }

    public function register(string $actionsEnumPath): void
    {
        if (!enum_exists($actionsEnumPath)) {
            throw new ApplicationException('an enum instance is expected for the action record.');
        }
        foreach ($actionsEnumPath::members() as $action) {
            $this->messages->put($action->value, $action);
        }
    }

    public function exists(string $action): bool
    {
        return $this->messages->hasKey($action);
    }

    public function create(string $action, array $data): MessageInterface
    {
        if (!$this->exists($action)) {
            throw new ApplicationException('the action is not registered.');
        }
        $message = $this->messages->get($action);
        return empty($data) ? new $message() : new $message(...$data);
    }

    public function list(): array
    {
        return $this->messages->values()->toArray();
    }
}
