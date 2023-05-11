<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Validators;

use App\Application\Validators\{AddProductToOrderValidator, ApplyDiscountToProductValidator, CreateOrderValidator};
use Tests\BaseTestCase;

final class CreateOrderValidatorTest extends BaseTestCase
{
    public function testCreate(): void
    {
        $validator = CreateOrderValidator::create();
        $this->assertInstanceOf(CreateOrderValidator::class, $validator);
    }
}
