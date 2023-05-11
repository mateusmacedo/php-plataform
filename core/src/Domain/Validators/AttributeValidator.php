<?php

declare(strict_types=1);

namespace Core\Domain\Validators;

use Core\Domain\DomainException;

/**
 * Class AttributeValidator.
 */
class AttributeValidator extends AbstractValidator
{
    public function __construct(public readonly string $attribute, public readonly AbstractValidator $validator)
    {
    }

    /**
     * @param mixed $input
     *
     * @return bool
     */
    public function validate(mixed $input): bool
    {
        return $this->validator->validate($input);
    }

    /**
     * @return DomainException
     */
    public function getError(): DomainException
    {
        return $this->validator->getError();
    }

    /**
     * @return mixed
     */
    public function getErrorMessage()
    {
        return $this->validator->getErrorMessage();
    }
}
