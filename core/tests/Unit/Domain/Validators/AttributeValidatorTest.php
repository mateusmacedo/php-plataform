<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Validators;

use Core\Domain\Validators\{AttributeValidator, Validator};
use Tests\TestCase;

class AttributeValidatorTest extends TestCase
{
    public function testConstructor(): void
    {
        $validator = $this->createMock(Validator::class);
        $attributeValidator = new AttributeValidator('test', $validator);
        $this->assertInstanceOf(AttributeValidator::class, $attributeValidator);
    }

    public function testGetAttribute(): void
    {
        $validator = $this->createMock(Validator::class);
        $attributeValidator = new AttributeValidator('test', $validator);
        $this->assertEquals('test', $attributeValidator->getAttribute());
    }

    public function testGetValidator(): void
    {
        $validator = $this->createMock(Validator::class);
        $attributeValidator = new AttributeValidator('test', $validator);
        $this->assertInstanceOf(Validator::class, $attributeValidator->getValidator());
    }

    public function testValidate(): void
    {
        $validator = $this->createMock(Validator::class);
        $validator->method('validate')->willReturn(true);
        $attributeValidator = new AttributeValidator('test', $validator);
        $this->assertTrue($attributeValidator->validate(['test' => 'test']));
    }

    public function testValidateFail(): void
    {
        $validator = $this->createMock(Validator::class);
        $validator->method('validate')->willReturn(false);
        $validator->method('getErrorMessage')->willReturn('test');
        $attributeValidator = new AttributeValidator('test', $validator);
        $this->assertFalse($attributeValidator->validate(['test' => 'test']));
        $this->assertEquals('test', $attributeValidator->getErrorMessage());
    }
}
