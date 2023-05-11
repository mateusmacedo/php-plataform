<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Validators;

use App\Application\Validators\{AddProductToOrderValidator, ApplyDiscountToProductValidator, CreateOrderValidator, CreateProductValidator, FindDiscountForProductValidator, FindOrderByIdValidator, FindProductByIdValidator, FindProductsByIdsValidator, RemoveProductFromOrderValidator};
use Tests\BaseTestCase;

final class RemoveProductFromOrderValidatorTest extends BaseTestCase
{
    public function testCreate(): void
    {
        $validator = RemoveProductFromOrderValidator::create();
        $this->assertInstanceOf(RemoveProductFromOrderValidator::class, $validator);
    }
}
