<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Commands;

use App\Application\Commands\ApplyDiscountToProductCommand;
use Tests\BaseTestCase;

final class ApplyDiscountToProductCommandTest extends BaseTestCase
{
    public function testConstructorSetsProperties()
    {
        $discount = 0.1;
        $productId = '123';
        $command = new ApplyDiscountToProductCommand($discount, $productId);

        $this->assertSame($discount, $command->discount);
        $this->assertSame($productId, $command->productId);
    }
}
