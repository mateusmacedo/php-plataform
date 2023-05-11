<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Specifications;

use Core\Domain\Specifications\{AndSpecification, SpecificationInterface};
use Tests\BaseTestCase;

final class AndSpecificationTest extends BaseTestCase
{
    public function testBothSpecificationsSatisfied()
    {
        $leftSpecification = $this->createMock(SpecificationInterface::class);
        $leftSpecification->method('isSatisfiedBy')->willReturn(true);

        $rightSpecification = $this->createMock(SpecificationInterface::class);
        $rightSpecification->method('isSatisfiedBy')->willReturn(true);

        $andSpecification = new AndSpecification($leftSpecification, $rightSpecification);

        $this->assertTrue($andSpecification->isSatisfiedBy('target'));
    }

    public function testLeftSpecificationNotSatisfied()
    {
        $leftSpecification = $this->createMock(SpecificationInterface::class);
        $leftSpecification->method('isSatisfiedBy')->willReturn(false);

        $rightSpecification = $this->createMock(SpecificationInterface::class);
        $rightSpecification->method('isSatisfiedBy')->willReturn(true);

        $andSpecification = new AndSpecification($leftSpecification, $rightSpecification);

        $this->assertFalse($andSpecification->isSatisfiedBy('target'));
    }

    public function testRightSpecificationNotSatisfied()
    {
        $leftSpecification = $this->createMock(SpecificationInterface::class);
        $leftSpecification->method('isSatisfiedBy')->willReturn(true);

        $rightSpecification = $this->createMock(SpecificationInterface::class);
        $rightSpecification->method('isSatisfiedBy')->willReturn(false);

        $andSpecification = new AndSpecification($leftSpecification, $rightSpecification);

        $this->assertFalse($andSpecification->isSatisfiedBy('target'));
    }

    public function testBothSpecificationsNotSatisfied()
    {
        $leftSpecification = $this->createMock(SpecificationInterface::class);
        $leftSpecification->method('isSatisfiedBy')->willReturn(false);

        $rightSpecification = $this->createMock(SpecificationInterface::class);
        $rightSpecification->method('isSatisfiedBy')->willReturn(false);

        $andSpecification = new AndSpecification($leftSpecification, $rightSpecification);

        $this->assertFalse($andSpecification->isSatisfiedBy('target'));
    }
}
