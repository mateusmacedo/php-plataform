<?php

declare(strict_types=1);

namespace Core\Domain\Validators;

use Core\Domain\DomainException;
use Ds\Map;
use Generator;

class ValidatorComposite extends AbstractValidator
{
    protected Map $validators;

    public function __construct(array $validators = [])
    {
        $this->error = new Map();
        $this->validators = new Map();
        foreach ($validators as $validator) {
            if (!$validator instanceof AbstractValidator) {
                throw new DomainException('Invalid validator');
            }
            $this->addValidator($validator);
        }
    }

    public function addValidator(AbstractValidator $validator): void
    {

        if ($this->validators->hasKey(get_class($validator))) {
            throw new DomainException('Validator already exists');
        }
        if (($validator instanceof AttributeValidatorComposite && $validator->attribute !== null) || ($validator instanceof AttributeValidator && $validator->attribute !== null)) {
            $this->validators->put(get_class($validator) . $validator->attribute, $validator);
        } else {
            $this->validators->put(get_class($validator), $validator);
        }
    }

    public function getValidator(string $validator): AbstractValidator
    {
        if (!$this->validators->hasKey($validator)) {
            throw new DomainException('Validator does not exist');
        }
        return $this->validators->get($validator);
    }

    public function getValidators(): Map
    {
        return $this->validators;
    }

    public function removeValidator(AbstractValidator $validator): void
    {
        if (!$this->validators->hasKey(get_class($validator))) {
            throw new DomainException('Validator does not exist');
        }
        $this->validators->remove(get_class($validator));
    }

    public function validate(mixed $input): bool
    {
        $this->error->clear();
        $result = true;
        foreach ($this->validators as $validator) {
            if (!$validator->validate($input)) {
                $this->error->put(get_class($validator), $validator->getError());
                $result = false;
            }
        }
        return $result;
    }

    public function getErrorMessage(): array
    {
        $errors = [];
        foreach ($this->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        return $errors;
    }

    public function getError(): DomainException|null
    {
        if (!$this->error->isEmpty()) {
            return new DomainException(implode(', ', $this->getErrorMessage()));
        }
        return null;
    }

    private function getErrors(): Generator
    {
        foreach ($this->error as $error) {
            yield $error;
        }
    }
}
