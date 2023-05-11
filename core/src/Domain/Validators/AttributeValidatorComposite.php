<?php

declare(strict_types=1);

namespace Core\Domain\Validators;

use Core\Domain\DomainException;

class AttributeValidatorComposite extends ValidatorComposite
{
    public function __construct(public readonly string $attribute, array $validators = [])
    {
        parent::__construct($validators);
    }

    public function validate(mixed $input): bool
    {
        if (is_object($input)) {
            $input = (array) $input;
        }

        if (!array_key_exists($this->attribute, $input)) {
            return true;
        }

        $errors = [];
        foreach ($this->getValidators() as $validator) {
            if (!$validator->validate($input[$this->attribute])) {
                $errors[] = $validator->getError();
            }
        }
        if (!empty($errors)) {
            $this->error->put($this->attribute, $errors);
        }
        return $this->error->isEmpty();
    }

    public function getError(): DomainException | null
    {
        if (!$this->error->isEmpty()) {
            $implodedErrors = implode(', ', $this->getErrorMessage()[$this->attribute]);
            return new DomainException($implodedErrors);
        }
        return null;
    }

    public function getErrorMessage(): array
    {
        $errors = [];
        foreach ($this->error as $attributeKey => $attributeErros) {
            foreach ($attributeErros as $value) {
                $errors[$attributeKey][] = $value->getMessage();
            }
        }
        return $errors;
    }
}
