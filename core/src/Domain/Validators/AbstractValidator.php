<?php

declare(strict_types=1);

namespace Core\Domain\Validators;

use Core\Domain\DomainException;

abstract class AbstractValidator
{
    protected $error;

    abstract public function validate(mixed $input): bool;

    abstract public function getError(): DomainException|null;

    abstract public function getErrorMessage();
}
