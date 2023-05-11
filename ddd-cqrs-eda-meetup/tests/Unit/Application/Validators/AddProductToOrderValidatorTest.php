<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Validators;

use App\Application\Validators\AddProductToOrderValidator;
use Tests\BaseTestCase;

final class AddProductToOrderValidatorTest extends BaseTestCase
{
    public function testCreate(): void
    {
        $validator = AddProductToOrderValidator::create();
        $this->assertInstanceOf(AddProductToOrderValidator::class, $validator);
    }
}
