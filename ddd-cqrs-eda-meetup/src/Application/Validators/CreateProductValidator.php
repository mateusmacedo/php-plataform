<?php

declare(strict_types=1);

namespace App\Application\Validators;
use Core\Domain\FactoryMethodInterface;
use Core\Domain\Validators\AbstractValidatorComposite;
use Core\Domain\Validators\AttributeValidatorComposite;

final class CreateProductValidator extends AbstractValidatorComposite implements FactoryMethodInterface
{
    public static function create(): self
    {
        $validators = [
            new AttributeValidatorComposite('id', []),
            new AttributeValidatorComposite('name', []),
            new AttributeValidatorComposite('description', []),
            new AttributeValidatorComposite('price', []),
        ];
        return new self($validators);
    }
}