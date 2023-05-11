<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Validators;

use Core\Domain\DomainException;
use Core\Domain\Validators\{AbstractValidator, AttributeValidator};
use Tests\BaseTestCase;
use Tests\Unit\Domain\Validators\Stubs\ValidatorStubOne;

final class AttributeValidatorTest extends BaseTestCase
{
    public AttributeValidator $sut;
    public string $attribute;
    public DomainException $domainException;
    public AbstractValidator $validator;

    public function setUp(): void
    {
        parent::setUp();
        $this->attribute = 'attribute';
        $this->domainException = new DomainException('Validator Domain Error Message One');
        $this->validator = new ValidatorStubOne();
        $this->sut = new AttributeValidator($this->attribute, $this->validator);
    }

    public function testValidate(): void
    {
        $this->assertTrue($this->sut->validate('input'));
    }

    public function testGetError(): void
    {
        $this->assertEquals($this->domainException, $this->sut->getError());
    }

    public function testGetErrorMessage(): void
    {
        $this->assertEquals('Validator Domain Error Message One', $this->sut->getErrorMessage());
    }
}
