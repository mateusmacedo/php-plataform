<?php

declare(strict_types=1);

namespace App\Application\Validators;

use Core\Domain\Validators\{AttributeValidatorComposite, ValidatorComposite};
use Core\Domain\{AbstractFactoryMethod, FactoryMethodInterface};

final class FindProductByIdValidator extends ValidatorComposite implements FactoryMethodInterface
{
    public static function create($data = null): self
    {
        $validators = [
            new AttributeValidatorComposite('productId', []),
        ];
        return new self($validators);
    }
}
