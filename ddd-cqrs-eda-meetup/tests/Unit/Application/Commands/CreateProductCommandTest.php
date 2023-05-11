<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Commands;

use App\Application\Commands\CreateProductCommand;
use Tests\BaseTestCase;

final class CreateProductCommandTest extends BaseTestCase
{
    public function testConstructorSetsProperties()
    {
        $id = '123';
        $name = 'test';
        $description = 'test';
        $price = 10.0;
        $command = new CreateProductCommand($id, $name, $description, $price);

        $this->assertSame($id, $command->id);
        $this->assertSame($name, $command->name);
        $this->assertSame($description, $command->description);
        $this->assertSame($price, $command->price);
    }
}
