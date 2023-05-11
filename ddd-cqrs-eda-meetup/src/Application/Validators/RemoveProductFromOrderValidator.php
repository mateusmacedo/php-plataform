<?php

declare(strict_types=1);

namespace App\Application\Validators;

use Core\Domain\FactoryMethodInterface;
use Core\Domain\Validators\{AttributeValidatorComposite, ValidatorComposite};

final class RemoveProductFromOrderValidator extends ValidatorComposite implements FactoryMethodInterface
{
    public static function create($data = null): self
    {
        $validators = [
            new AttributeValidatorComposite('productId', []),
            new AttributeValidatorComposite('orderId', []),
        ];
        return new self($validators);
    }
}
