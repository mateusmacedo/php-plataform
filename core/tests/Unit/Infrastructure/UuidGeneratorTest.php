<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure;

use Core\Infrastructure\UuidGenerator;
use Tests\BaseTestCase;
use Ramsey\Uuid\Uuid;

class UuidGeneratorTest extends BaseTestCase
{
    public function testGenerateReturnsString(): void
    {
        $uuid = UuidGenerator::generate();
        $this->assertIsString($uuid);
    }

    public function testGenerateReturnsUuid(): void
    {
        $uuid = UuidGenerator::generate();
        $this->assertTrue(Uuid::isValid($uuid));
    }
}
