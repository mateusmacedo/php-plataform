<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Validators;

use Core\Domain\{
    DomainException,
    Validators\AbstractValidator,
    Validators\ValidatorComposite
};
use stdClass;
use Tests\BaseTestCase;
use Tests\Unit\Domain\Validators\Stubs\{
    ValidatorStubOne,
    ValidatorStubTwo
};

final class ValidatorCompositeTest extends BaseTestCase
{
    public ValidatorComposite $sut;
    public AbstractValidator $validatorOne;
    public AbstractValidator $validatorTwo;

    public function setUp(): void
    {
        parent::setUp();
        $this->validatorOne = new ValidatorStubOne();
        $this->validatorTwo = new ValidatorStubTwo();
        $this->sut = new ValidatorComposite([
            $this->validatorOne,
            $this->validatorTwo
        ]);
    }

    public function testConstructorWithCorrectParams(): void
    {
        $this->assertInstanceOf(ValidatorComposite::class, $this->sut);
    }

    public function testConstructorWithoutParams(): void
    {
        $this->sut = new ValidatorComposite();
        $this->assertInstanceOf(ValidatorComposite::class, $this->sut);
        $this->assertEmpty($this->sut->getValidators());
    }

    public function testConstructorWithInvalidParams(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Invalid validator');
        $this->sut = new ValidatorComposite([
            new stdClass()
        ]);
    }

    public function testAddValidator(): void
    {
        $this->sut = new ValidatorComposite();
        $this->assertCount(0, $this->sut->getValidators()->toArray());
        $this->sut->addValidator(new ValidatorStubOne());
        $this->assertCount(1, $this->sut->getValidators()->toArray());
    }

    public function testAddValidatorWithInvalidParams(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Validator already exists');
        $this->sut->addValidator(new ValidatorStubOne());
        $this->sut->addValidator(new ValidatorStubOne());
    }

    public function testGetValidator(): void
    {
        $this->assertInstanceOf(
            ValidatorStubOne::class,
            $this->sut->getValidator(ValidatorStubOne::class)
        );
    }

    public function testGetValidatorWithInvalidParams(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Validator does not exist');
        $this->sut->getValidator('InvalidValidator');
    }

    public function testGetValidators(): void
    {
        $this->assertCount(2, $this->sut->getValidators());
    }

    public function testRemoveValidator(): void
    {
        $this->sut->removeValidator($this->validatorOne);
        $this->assertCount(1, $this->sut->getValidators());
    }

    public function testRemoveValidatorWithInvalidParams(): void
    {
        $this->sut = new ValidatorComposite();
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Validator does not exist');
        $this->sut->removeValidator(new ValidatorStubOne());
    }

    public function testValidate(): void
    {
        $this->assertTrue($this->sut->validate('valid'));
    }

    public function testValidateWithInvalidParams(): void
    {
        $mockedValidator = $this->createMock(AbstractValidator::class);
        $mockedValidator->method('validate')->willReturn(false);
        $mockedValidator->method('getError')->willReturn(new DomainException('Invalid'));

        $this->sut = new ValidatorComposite();
        $this->sut->addValidator($mockedValidator);
        $this->assertFalse($this->sut->validate('invalid'));
    }

    public function testGetErrorWithValidParams(): void
    {
        $this->sut->validate('valid');
        $this->assertNull($this->sut->getError());
    }

    public function testGetErrorWithInvalidParams(): void
    {
        $mockedValidator = $this->createMock(AbstractValidator::class);
        $mockedValidator->method('validate')->willReturn(false);
        $mockedValidator->method('getError')->willReturn(new DomainException('Invalid'));

        $this->sut = new ValidatorComposite();
        $this->sut->addValidator($mockedValidator);

        $this->sut->validate('invalid');
        $this->assertInstanceOf(DomainException::class, $this->sut->getError());
        $this->assertEquals('Invalid', $this->sut->getError()->getMessage());
    }

    public function testGetErrorWithInvalidParamsAndMultipleErros(): void
    {
        $mockedValidatorOne = $this->createMock(ValidatorStubOne::class);
        $mockedValidatorOne->method('validate')->willReturn(false);
        $mockedValidatorOne->method('getError')->willReturn(new DomainException('Invalid One'));
        $mockedValidatorTwo = $this->createMock(ValidatorStubTwo::class);
        $mockedValidatorTwo->method('validate')->willReturn(false);
        $mockedValidatorTwo->method('getError')->willReturn(new DomainException('Invalid Two'));

        $this->sut = new ValidatorComposite();
        $this->sut->addValidator($mockedValidatorOne);
        $this->sut->addValidator($mockedValidatorTwo);

        $this->sut->validate('invalid');
        $this->assertInstanceOf(DomainException::class, $this->sut->getError());
        $this->assertEquals('Invalid One, Invalid Two', $this->sut->getError()->getMessage());
    }
}
