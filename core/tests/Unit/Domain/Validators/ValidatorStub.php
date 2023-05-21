<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Validators;
use Closure;
use Core\Domain\Validators\ValidatorInterface;
use Core\Utils\DeepCloneTrait;
use ReflectionClass;

class ValidatorStub implements ValidatorInterface
{
    use DeepCloneTrait;

    public function __construct(
        private ?Closure $isValid,
        private ?array $messages,
        private ?string $key
    ) {
    }

	public function isValid($value) {
        if (isset($this->isValid)) {
            return ($this->isValid)($value);
        }
        return true;
	}

	public function getMessages()
    {
        if (isset($this->messages)) {
            return $this->messages;
        }
        return [];
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
}
