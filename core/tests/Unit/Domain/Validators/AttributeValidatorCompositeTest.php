<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Validators;

use Core\Domain\DomainException;
use Core\Domain\Validators\{AbstractValidator, AttributeValidatorComposite};
use Tests\BaseTestCase;
use Tests\Unit\Domain\Validators\Stubs\{ValidatorStubOne, ValidatorStubTwo};

final class AttributeValidatorCompositeTest extends BaseTestCase
{
    public AttributeValidatorComposite $sut;
    public string $attribute;
    public DomainException $domainException;
    public AbstractValidator $validatorOne;
    public AbstractValidator $validatorTwo;

    public function setUp(): void
    {
        parent::setUp();
        $this->attribute = 'attribute';
        $this->domainException = new DomainException('');
        $this->validatorOne = new ValidatorStubOne();
        $this->validatorTwo = new ValidatorStubTwo();
        $this->sut = new AttributeValidatorComposite($this->attribute, [$this->validatorOne, $this->validatorTwo]);
    }

    public function testConstruct(): void
    {
        $this->sut = new AttributeValidatorComposite($this->attribute);
        $this->assertCount(0, $this->sut->getValidators());
    }

    public function testConstructWithValidators(): void
    {
        $this->assertCount(2, $this->sut->getValidators());
    }

    public function testConstructWithInvalidValidator(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Invalid validator');
        $this->sut = new AttributeValidatorComposite($this->attribute, [$this->validatorOne, 'invalidValidator']);
    }

    public function testGetValidator(): void
    {
        $this->assertEquals($this->validatorOne, $this->sut->getValidator(ValidatorStubOne::class));
    }

    public function testGetValidatorDoesNotExist(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Validator does not exist');
        $this->sut->getValidator('invalidValidator');
    }

    public function testAddValidator(): void
    {
        $this->sut = new AttributeValidatorComposite($this->attribute);
        $this->sut->addValidator($this->validatorOne);
        $this->assertCount(1, $this->sut->getValidators());
        $this->assertEquals($this->validatorOne, $this->sut->getValidator(ValidatorStubOne::class));
    }

    public function testAddValidatorAlreadyExists(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Validator already exists');
        $this->sut->addValidator($this->validatorOne);
    }

    public function testRemoveValidator(): void
    {
        $this->sut = new AttributeValidatorComposite($this->attribute, [$this->validatorOne]);
        $this->sut->removeValidator($this->validatorOne);
        $this->assertCount(0, $this->sut->getValidators());
    }

    public function testRemoveValidatorDoesNotExist(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Validator does not exist');
        $this->sut = new AttributeValidatorComposite($this->attribute);
        $this->sut->removeValidator($this->validatorOne);
    }

    public function testGetError(): void
    {
        $this->assertEquals($this->domainException, $this->sut->getError());
    }

    public function testValidateWithArrayData(): void
    {
        $this->assertTrue($this->sut->validate(['attribute' => 'value']));
        $this->assertCount(0, $this->sut->getErrorMessage());
    }

    public function testValidateWithObjectData(): void
    {
        $this->assertTrue($this->sut->validate((object) ['attribute' => 'value']));
        $this->assertCount(0, $this->sut->getErrorMessage());
    }

    public function testValidateWithUnexistingAttribute(): void
    {
        $this->assertTrue($this->sut->validate(['unexistingAttribute' => 'value']));
    }

    public function testValidateWithErrors(): void
    {
        $mockedValidatorOne = $this->createMock(ValidatorStubOne::class);
        $mockedValidatorOne->method('validate')->willReturn(false);
        $mockedValidatorOne->method('getError')->willReturn(new DomainException('Validator Domain Error Message Mocked One'));

        $mockedValidatorTwo = $this->createMock(ValidatorStubTwo::class);
        $mockedValidatorTwo->method('validate')->willReturn(false);
        $mockedValidatorTwo->method('getError')->willReturn(new DomainException('Validator Domain Error Message Mocked Two'));

        $this->sut = new AttributeValidatorComposite($this->attribute);
        $this->sut->addValidator($mockedValidatorOne);
        $this->sut->addValidator($mockedValidatorTwo);

        $this->assertFalse($this->sut->validate(['attribute' => 'value']));
    }

    public function testValidateWithOneError(): void
    {
        $mockedValidatorOne = $this->createMock(ValidatorStubOne::class);
        $mockedValidatorOne->method('validate')->willReturn(true);

        $mockedValidatorTwo = $this->createMock(ValidatorStubTwo::class);
        $mockedValidatorTwo->method('validate')->willReturn(false);
        $mockedValidatorTwo->method('getError')->willReturn(new DomainException('Validator Domain Error Message Mocked Two'));

        $this->sut = new AttributeValidatorComposite($this->attribute);
        $this->sut->addValidator($mockedValidatorOne);
        $this->sut->addValidator($mockedValidatorTwo);

        $this->assertFalse($this->sut->validate(['attribute' => 'value']));
        $this->assertCount(1, $this->sut->getErrorMessage());
    }
}
