<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Validators;

use App\Application\Validators\{AddProductToOrderValidator, ApplyDiscountToProductValidator, CreateOrderValidator, CreateProductValidator, FindDiscountForProductValidator};
use Tests\BaseTestCase;

final class FindDiscountForProductValidatorTest extends BaseTestCase
{
    public function testCreate(): void
    {
        $validator = FindDiscountForProductValidator::create();
        $this->assertInstanceOf(FindDiscountForProductValidator::class, $validator);
    }
}
