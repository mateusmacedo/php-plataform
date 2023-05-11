<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Validators;

use App\Application\Validators\{AddProductToOrderValidator, ApplyDiscountToProductValidator, CreateOrderValidator, CreateProductValidator, FindDiscountForProductValidator, FindOrderByIdValidator};
use Tests\BaseTestCase;

final class FindOrderByIdValidatorTest extends BaseTestCase
{
    public function testCreate(): void
    {
        $validator = FindOrderByIdValidator::create();
        $this->assertInstanceOf(FindOrderByIdValidator::class, $validator);
    }
}
