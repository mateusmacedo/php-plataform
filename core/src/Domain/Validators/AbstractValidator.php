<?php

declare(strict_types=1);

namespace Core\Domain\Validators;

abstract class AbstractValidator
{
    protected $error;

    abstract public function validate(mixed $input): bool;

    abstract public function getError();

    abstract public function getErrorMessage();
}
