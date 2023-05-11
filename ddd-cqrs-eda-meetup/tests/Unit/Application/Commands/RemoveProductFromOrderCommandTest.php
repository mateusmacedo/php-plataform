<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Commands;

use App\Application\Commands\RemoveProductFromOrderCommand;
use Tests\BaseTestCase;

final class RemoveProductFromOrderCommandTest extends BaseTestCase
{
    public function testConstructorSetsProperties()
    {
        $orderId = '123';
        $productId = '123';
        $command = new RemoveProductFromOrderCommand($orderId, $productId);

        $this->assertSame($orderId, $command->orderId);
        $this->assertSame($productId, $command->productId);
    }
}
