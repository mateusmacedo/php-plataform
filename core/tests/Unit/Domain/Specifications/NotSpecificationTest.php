<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Specifications;

use Core\Domain\Specifications\{NotSpecification, Specification};
use Tests\TestCase;

class NotSpecificationTest extends TestCase
{
    public function testIsSatisfiedBy(): void
    {
        $spec = $this->createMock(Specification::class);
        $spec->method('isSatisfiedBy')->willReturn(true);
        $notSpec = new NotSpecification($spec);
        $this->assertFalse($notSpec->isSatisfiedBy('foo'));
    }

    public function testIsNotSatisfiedBy(): void
    {
        $spec = $this->createMock(Specification::class);
        $spec->method('isSatisfiedBy')->willReturn(false);
        $notSpec = new NotSpecification($spec);
        $this->assertTrue($notSpec->isSatisfiedBy('foo'));
    }
}
