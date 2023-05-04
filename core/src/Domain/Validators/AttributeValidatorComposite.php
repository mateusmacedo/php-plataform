<?php

declare(strict_types=1);

namespace Core\Domain\Validators;

use Core\Domain\DomainException;

class AttributeValidatorComposite extends AbstractValidatorComposite
{
    public function __construct(public readonly string $attribute, array $validators = [])
    {
        parent::__construct(...$validators);
    }

    public function validate(mixed $input): bool
    {
        foreach ($input as $key => $value) {
            if ($key === $this->attribute) {
                foreach ($this->getValidators() as $validator) {
                    if (!$validator->validate($value)) {
                        $this->error->put($key, $validator->getError());
                    }
                }
            }
        }
        return $this->error->isEmpty();
    }

    public function getError(): DomainException
    {
        return new DomainException(implode(', ', $this->getErrorMessage()));
    }

    public function getErrorMessage(): array
    {
        $errors = [];
        foreach ($this->error as $key => $value) {
            $errors[$key] = $value;
        }
        return $errors;
    }
}
