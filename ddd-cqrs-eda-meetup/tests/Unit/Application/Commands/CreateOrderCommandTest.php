<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Commands;

use App\Application\Commands\CreateOrderCommand;
use Tests\BaseTestCase;

final class CreateOrderCommandTest extends BaseTestCase
{
    public function testCreateOrderCommand(): void
    {
        $command = new CreateOrderCommand(
            '123',
            []
        );
        self::assertEquals('123', $command->id);
    }
}
