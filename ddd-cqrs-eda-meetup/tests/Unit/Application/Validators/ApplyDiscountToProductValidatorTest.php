<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Validators;

use App\Application\Validators\{AddProductToOrderValidator, ApplyDiscountToProductValidator};
use Tests\BaseTestCase;

final class ApplyDiscountToProductValidatorTest extends BaseTestCase
{
    public function testCreate(): void
    {
        $validator = ApplyDiscountToProductValidator::create();
        $this->assertInstanceOf(ApplyDiscountToProductValidator::class, $validator);
    }
}
