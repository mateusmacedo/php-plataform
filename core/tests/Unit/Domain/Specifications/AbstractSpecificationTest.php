<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Specifications;

use Core\Domain\Specifications\{AbstractSpecification, AndSpecification, NotSpecification, OrSpecification, SpecificationInterface};
use Tests\BaseTestCase;

final class AbstractSpecificationTest extends BaseTestCase
{
    public AbstractSpecification $sut;

    public function setUp(): void
    {
        parent::setUp();
        $this->sut = $this->getMockForAbstractClass(AbstractSpecification::class);
    }

    public function testAnd()
    {
        $specification = $this->createMock(SpecificationInterface::class);
        $andSpecification = $this->sut->and($specification);
        $this->assertInstanceOf(AndSpecification::class, $andSpecification);
    }

    public function testOr()
    {
        $specification = $this->createMock(SpecificationInterface::class);
        $orSpecification = $this->sut->or($specification);
        $this->assertInstanceOf(OrSpecification::class, $orSpecification);
    }

    public function testNot()
    {
        $notSpecification = $this->sut->not();
        $this->assertInstanceOf(NotSpecification::class, $notSpecification);
    }
}
