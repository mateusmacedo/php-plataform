<?php

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }
}
