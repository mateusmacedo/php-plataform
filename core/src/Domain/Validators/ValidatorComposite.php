<?php

declare(strict_types=1);

namespace Core\Domain\Validators;

use Core\Domain\DomainException;
use Core\Utils\DeepCloneTrait;
use Countable;
use DeepCopy\DeepCopy;
use Ds\Map;
use Generator;
use IteratorAggregate;
use ReflectionClass;
use Traversable;

class ValidatorComposite implements ValidatorInterface, Countable, IteratorAggregate
{
    use DeepCloneTrait;
    protected Map $validators;
    protected Map $messages;

    public function __construct(private ?string $key=null)
    {
        $this->validators = new Map();
        $this->messages = new Map();
    }

    public function count(): int
    {
        return $this->validators->count();
    }

    public function getIterator(): Traversable|array
    {
        return $this->validators->getIterator();
    }

    public function getKey(): string
    {
        if (isset($this->key)) {
            return $this->key;
        }

        $this->key = (new ReflectionClass($this))->getShortName();
        $length = strlen($this->key);
        $stringSpace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $stringSpace[rand(0, strlen($stringSpace) - 1)];
        }

        $this->key = "{$this->key}.{$randomString}";

        return $this->key;
	}

    public function addValidator(ValidatorInterface $validator): self
    {
        $key = $validator->getKey();
        if ($this->validators->hasKey($key)) {
            throw new DomainException("Validator {$key} already exists");
        }
        $this->validators->put($key, $validator);
        return $this;
    }

    public function removeValidator(ValidatorInterface $validator): self
    {
        $key = $validator->getKey();
        if (!isset($this->validators[$key])) {
            throw new DomainException("Validator {$key} not found");
        }
        unset($this->validators[$key]);
        return $this;
    }

    public function isValid($value)
    {
        if (is_array($value)) {
            return $this->isValidRecursive($value);
        }
        $this->messages->clear();
        foreach ($this as $validator) {
            if (!$validator->isValid($value)) {
                $this->messages->put($validator->getKey(), $validator->getMessages());
            }
        }
        return $this->messages->isEmpty();
    }

    private function isValidRecursive(Traversable | array $value)
    {
        foreach($value as $key => $item) {
            $clone = $this->clone();
            if (!$clone->isValid($item)) {
                $this->messages->put($key, $clone->getMessages());
            }
        }
        return $this->messages->isEmpty();
    }

    public function getMessages(): array
    {
        $messages = [];
        foreach ($this->messages as $key => $message) {
            $messages[$key] = $message;
        }
        return $messages;
    }
}
