<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Validators\Stubs;

use Core\Domain\DomainException;
use Core\Domain\Validators\AbstractValidator;

class ValidatorStubTwo extends AbstractValidator
{
    /**
     * @param mixed $input
     *
     * @return bool
     */
    public function validate(mixed $input): bool
    {
        return true;
    }

    /**
     * @return \Core\Domain\DomainException
     */
    public function getError(): DomainException
    {
        return new DomainException($this->getErrorMessage());
    }

    /**
     * @return mixed
     */
    public function getErrorMessage()
    {
        return 'Validator Domain Error Message Two';
    }
}
