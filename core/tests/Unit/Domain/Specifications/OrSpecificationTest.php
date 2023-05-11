<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Specifications;

use Core\Domain\Specifications\{OrSpecification, SpecificationInterface};
use Tests\BaseTestCase;

final class OrSpecificationTest extends BaseTestCase
{
    public function testBothSpecificationsNotSatisfied()
    {
        $leftSpecification = $this->createMock(SpecificationInterface::class);
        $leftSpecification->method('isSatisfiedBy')->willReturn(false);

        $rightSpecification = $this->createMock(SpecificationInterface::class);
        $rightSpecification->method('isSatisfiedBy')->willReturn(false);

        $orSpecification = new OrSpecification($leftSpecification, $rightSpecification);

        $this->assertFalse($orSpecification->isSatisfiedBy('target'));
    }

    public function testLeftSpecificationSatisfied()
    {
        $leftSpecification = $this->createMock(SpecificationInterface::class);
        $leftSpecification->method('isSatisfiedBy')->willReturn(true);

        $rightSpecification = $this->createMock(SpecificationInterface::class);
        $rightSpecification->method('isSatisfiedBy')->willReturn(false);

        $orSpecification = new OrSpecification($leftSpecification, $rightSpecification);

        $this->assertTrue($orSpecification->isSatisfiedBy('target'));
    }

    public function testRightSpecificationSatisfied()
    {
        $leftSpecification = $this->createMock(SpecificationInterface::class);
        $leftSpecification->method('isSatisfiedBy')->willReturn(false);

        $rightSpecification = $this->createMock(SpecificationInterface::class);
        $rightSpecification->method('isSatisfiedBy')->willReturn(true);

        $orSpecification = new OrSpecification($leftSpecification, $rightSpecification);

        $this->assertTrue($orSpecification->isSatisfiedBy('target'));
    }

    public function testBothSpecificationsSatisfied()
    {
        $leftSpecification = $this->createMock(SpecificationInterface::class);
        $leftSpecification->method('isSatisfiedBy')->willReturn(true);

        $rightSpecification = $this->createMock(SpecificationInterface::class);
        $rightSpecification->method('isSatisfiedBy')->willReturn(true);

        $orSpecification = new OrSpecification($leftSpecification, $rightSpecification);

        $this->assertTrue($orSpecification->isSatisfiedBy('target'));
    }
}
