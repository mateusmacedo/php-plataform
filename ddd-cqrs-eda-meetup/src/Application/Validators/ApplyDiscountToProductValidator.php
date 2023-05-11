<?php

declare(strict_types=1);

namespace App\Application\Validators;

use Core\Domain\Validators\{AttributeValidatorComposite, ValidatorComposite};
use Core\Domain\{AbstractFactoryMethod, FactoryMethodInterface};

final class ApplyDiscountToProductValidator extends ValidatorComposite implements FactoryMethodInterface
{
    public static function create($data = null): self
    {
        $validators = [
            new AttributeValidatorComposite('discount', []),
            new AttributeValidatorComposite('productId', []),
        ];
        return new self($validators);
    }
}
