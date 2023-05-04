<?php

declare(strict_types=1);

namespace App\Application\Validators;
use Core\Domain\FactoryMethodInterface;
use Core\Domain\Validators\AbstractValidatorComposite;
use Core\Domain\Validators\AttributeValidatorComposite;

final class RemoveProductFromOrderValidator extends AbstractValidatorComposite implements FactoryMethodInterface
{
    public static function create(): self
    {
        $validators = [
            new AttributeValidatorComposite('productId', []),
            new AttributeValidatorComposite('orderId', []),
        ];
        return new self($validators);
    }
}