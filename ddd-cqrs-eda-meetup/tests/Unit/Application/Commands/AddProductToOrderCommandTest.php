<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Commands;

use App\Application\Commands\AddProductToOrderCommand;
use Tests\BaseTestCase;

class AddProductToOrderCommandTest extends BaseTestCase
{
    public function testConstructorSetsProperties()
    {
        $productId = '123';
        $orderId = '456';
        $command = new AddProductToOrderCommand($productId, $orderId);

        $this->assertSame($productId, $command->productId);
        $this->assertSame($orderId, $command->orderId);
    }
}
