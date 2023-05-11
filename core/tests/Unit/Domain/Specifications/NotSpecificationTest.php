<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Specifications;

use Core\Domain\Specifications\{NotSpecification, SpecificationInterface};
use Tests\BaseTestCase;

final class NotSpecificationTest extends BaseTestCase
{
    public function testSpecificationNotSatisfied()
    {
        $innerSpecification = $this->createMock(SpecificationInterface::class);
        $innerSpecification->method('isSatisfiedBy')->willReturn(false);

        $notSpecification = new NotSpecification($innerSpecification);

        $this->assertTrue($notSpecification->isSatisfiedBy('target'));
    }

    public function testSpecificationSatisfied()
    {
        $innerSpecification = $this->createMock(SpecificationInterface::class);
        $innerSpecification->method('isSatisfiedBy')->willReturn(true);

        $notSpecification = new NotSpecification($innerSpecification);

        $this->assertFalse($notSpecification->isSatisfiedBy('target'));
    }
}
