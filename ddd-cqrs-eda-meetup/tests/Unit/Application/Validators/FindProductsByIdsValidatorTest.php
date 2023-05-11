<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Validators;

use App\Application\Validators\{AddProductToOrderValidator, ApplyDiscountToProductValidator, CreateOrderValidator, CreateProductValidator, FindDiscountForProductValidator, FindOrderByIdValidator, FindProductByIdValidator, FindProductsByIdsValidator};
use Tests\BaseTestCase;

final class FindProductsByIdsValidatorTest extends BaseTestCase
{
    public function testCreate(): void
    {
        $validator = FindProductsByIdsValidator::create();
        $this->assertInstanceOf(FindProductsByIdsValidator::class, $validator);
    }
}
