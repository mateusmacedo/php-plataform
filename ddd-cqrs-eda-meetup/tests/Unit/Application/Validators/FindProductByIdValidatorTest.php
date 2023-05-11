<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Validators;

use App\Application\Validators\{AddProductToOrderValidator, ApplyDiscountToProductValidator, CreateOrderValidator, CreateProductValidator, FindDiscountForProductValidator, FindOrderByIdValidator, FindProductByIdValidator};
use Tests\BaseTestCase;

final class FindProductByIdValidatorTest extends BaseTestCase
{
    public function testCreate(): void
    {
        $validator = FindProductByIdValidator::create();
        $this->assertInstanceOf(FindProductByIdValidator::class, $validator);
    }
}
