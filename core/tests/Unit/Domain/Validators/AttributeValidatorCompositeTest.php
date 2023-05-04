<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Validators;

use Core\Domain\Validators\AttributeValidatorComposite;
use Tests\TestCase;

class AttributeValidatorCompositeTest extends TestCase
{
    public function testConstructor(): void
    {
        $attributeValidatorComposite = new AttributeValidatorComposite('test');
        $this->assertInstanceOf(AttributeValidatorComposite::class, $attributeValidatorComposite);
        $this->assertEquals('test', $attributeValidatorComposite->getAttribute());
        $this->assertCount(0, $attributeValidatorComposite->getValidators());
        $this->assertTrue($attributeValidatorComposite->validate(['test' => 'test']));
    }

    public function testAddValidator(): void
    {
        $validator1 = $this->createMock(\Core\Domain\Validators\Validator::class);
        $validator1->method('validate')->willReturn(true);
        $validator1->method('getErrorMessage')->willReturn('error1');

        $validator2 = $this->createMock(\Core\Domain\Validators\Validator::class);
        $validator2->method('validate')->willReturn(true);
        $validator2->method('getErrorMessage')->willReturn('error2');

        $attributeValidatorComposite = new AttributeValidatorComposite('test');
        $attributeValidatorComposite->addValidator($validator1);
        $attributeValidatorComposite->addValidator($validator2);

        $this->assertCount(2, $attributeValidatorComposite->getValidators());
    }

    public function testValidate(): void
    {
        $validator1 = $this->createMock(\Core\Domain\Validators\Validator::class);
        $validator1->method('validate')->willReturn(true);
        $validator1->method('getErrorMessage')->willReturn('error1');

        $validator2 = $this->createMock(\Core\Domain\Validators\Validator::class);
        $validator2->method('validate')->willReturn(true);
        $validator2->method('getErrorMessage')->willReturn('error2');

        $attributeValidatorComposite = new AttributeValidatorComposite('test');
        $attributeValidatorComposite->addValidator($validator1);
        $attributeValidatorComposite->addValidator($validator2);

        $this->assertTrue($attributeValidatorComposite->validate(['test' => 'test']));
    }

    public function testValidateFail(): void
    {
        $validator1 = $this->createMock(\Core\Domain\Validators\Validator::class);
        $validator1->method('validate')->willReturn(true);
        $validator1->method('getErrorMessage')->willReturn('error1');

        $validator2 = $this->createMock(\Core\Domain\Validators\Validator::class);
        $validator2->method('validate')->willReturn(false);
        $validator2->method('getErrorMessage')->willReturn('error2');

        $validator3 = $this->createMock(\Core\Domain\Validators\Validator::class);
        $validator3->method('validate')->willReturn(false);
        $validator3->method('getErrorMessage')->willReturn('error3');

        $attributeValidatorComposite = new AttributeValidatorComposite('test');
        $attributeValidatorComposite->addValidator($validator1);
        $attributeValidatorComposite->addValidator($validator2);
        $attributeValidatorComposite->addValidator($validator3);

        $this->assertFalse($attributeValidatorComposite->validate(['test' => 'test']));
        $this->assertEquals([
            'test' => [
                'error2',
                'error3'
            ]
        ], $attributeValidatorComposite->getErrorMessage());
    }

    public function testTreeValidate(): void
    {
        $validator1 = $this->createMock(\Core\Domain\Validators\Validator::class);
        $validator1->method('validate')->willReturn(true);

        $validator2 = $this->createMock(\Core\Domain\Validators\Validator::class);
        $validator2->method('validate')->willReturn(true);

        $validator3 = $this->createMock(\Core\Domain\Validators\Validator::class);
        $validator3->method('validate')->willReturn(true);

        $attributeValidatorComposite3 = new AttributeValidatorComposite('level1.1.1');
        $attributeValidatorComposite3->addValidator($validator1);
        $attributeValidatorComposite3->addValidator($validator2);
        $attributeValidatorComposite3->addValidator($validator3);

        $attributeValidatorComposite1 = new AttributeValidatorComposite('level1.1');
        $attributeValidatorComposite1->addValidator($attributeValidatorComposite3);

        $attributeValidatorComposite2 = new AttributeValidatorComposite('level1.2');
        $attributeValidatorComposite2->addValidator($validator1);
        $attributeValidatorComposite2->addValidator($validator2);
        $attributeValidatorComposite2->addValidator($validator3);

        $attributeValidatorComposite0 = new AttributeValidatorComposite('root');
        $attributeValidatorComposite0->addValidator($attributeValidatorComposite1);
        $attributeValidatorComposite0->addValidator($attributeValidatorComposite2);

        $this->assertTrue($attributeValidatorComposite0->validate([
            'root' => [
                'level1.1' => [
                    'level1.1.1' => 'test'
                ],
                'level1.2' => 'test'
            ]
        ]));
        $this->assertEquals([], $attributeValidatorComposite0->getErrorMessage());
    }

    public function testTreeValidateFail(): void
    {
        $validator1 = $this->createMock(\Core\Domain\Validators\Validator::class);
        $validator1->method('validate')->willReturn(false);
        $validator1->method('getErrorMessage')->willReturn('error1');

        $validator2 = $this->createMock(\Core\Domain\Validators\Validator::class);
        $validator2->method('validate')->willReturn(false);
        $validator2->method('getErrorMessage')->willReturn('error2');

        $validator3 = $this->createMock(\Core\Domain\Validators\Validator::class);
        $validator3->method('validate')->willReturn(false);
        $validator3->method('getErrorMessage')->willReturn('error3');

        $attributeValidatorComposite3 = new AttributeValidatorComposite('level1.1.1');
        $attributeValidatorComposite3->addValidator($validator1);
        $attributeValidatorComposite3->addValidator($validator2);
        $attributeValidatorComposite3->addValidator($validator3);

        $attributeValidatorComposite1 = new AttributeValidatorComposite('level1.1');
        $attributeValidatorComposite1->addValidator($attributeValidatorComposite3);

        $attributeValidatorComposite2 = new AttributeValidatorComposite('level1.2');
        $attributeValidatorComposite2->addValidator($validator1);
        $attributeValidatorComposite2->addValidator($validator2);
        $attributeValidatorComposite2->addValidator($validator3);

        $attributeValidatorComposite0 = new AttributeValidatorComposite('root');
        $attributeValidatorComposite0->addValidator($attributeValidatorComposite1);
        $attributeValidatorComposite0->addValidator($attributeValidatorComposite2);

        $this->assertFalse($attributeValidatorComposite0->validate(['root' => ['level1.1' => ['level1.1.1' => 'test'], 'level1.2' => 'test']]));
        $this->assertEquals([
            'root' => [
                'level1.1' => [
                    'level1.1.1' => [
                        'error1',
                        'error2',
                        'error3'
                    ]
                ],
                'level1.2' => [
                    'error1',
                    'error2',
                    'error3'
                ]
            ]
        ], $attributeValidatorComposite0->getErrorMessage());
    }
}
