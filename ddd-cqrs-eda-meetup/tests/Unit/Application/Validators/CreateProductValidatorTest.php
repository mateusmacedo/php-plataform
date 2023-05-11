<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Validators;

use App\Application\Validators\{AddProductToOrderValidator, ApplyDiscountToProductValidator, CreateOrderValidator, CreateProductValidator};
use Tests\BaseTestCase;

final class CreateProductValidatorTest extends BaseTestCase
{
    public function testCreate(): void
    {
        $validator = CreateProductValidator::create();
        $this->assertInstanceOf(CreateProductValidator::class, $validator);
    }
}
