<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Ecotone\Converters;

use Core\Infrastructure\Ecotone\Converters\PhpToJsonConverter;
use Ecotone\Messaging\Conversion\MediaType;
use Ecotone\Messaging\Handler\TypeDescriptor;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Infrastructure\Ecotone\Converters\Stubs\MessageStub;

class PhpToJsonConverterTest extends TestCase
{
    private PhpToJsonConverter $sut;

    protected function setUp(): void
    {
        $this->sut = new PhpToJsonConverter();
    }

    public function testShouldMatchWhenSourceIsPhpAndTargetIsJsonMediaType(): void
    {
        $sourceMediaType = MediaType::createApplicationXPHP();
        $targetMediaType = MediaType::createApplicationJson();
        $sourceType = TypeDescriptor::createStringType();
        $targetType = TypeDescriptor::createFromVariable([]);

        $result = $this->sut->matches($sourceType, $sourceMediaType, $targetType, $targetMediaType);

        $this->assertTrue($result);
    }

    public function testShouldMatchWhenSourceIsPhpAndTargetIsTextMediaType(): void
    {
        $sourceMediaType = MediaType::createApplicationXPHP();
        $targetMediaType = MediaType::createTextPlain();
        $sourceType = TypeDescriptor::createStringType();
        $targetType = TypeDescriptor::createFromVariable([]);

        $result = $this->sut->matches($sourceType, $sourceMediaType, $targetType, $targetMediaType);

        $this->assertTrue($result);
    }

    public function testShouldNotMatchWhenSourceIsNotPhp(): void
    {
        $sourceMediaType = MediaType::createApplicationJson();
        $targetMediaType = MediaType::createTextPlain();
        $sourceType = TypeDescriptor::createStringType();
        $targetType = TypeDescriptor::createFromVariable([]);

        $result = $this->sut->matches($sourceType, $sourceMediaType, $targetType, $targetMediaType);
        $this->assertFalse($result);
    }

    public function testConvertObject()
    {
        $source = (object)["name" => "John", "age" => 30];
        $sourceType = TypeDescriptor::createFromVariable($source);
        $sourceMediaType = MediaType::createApplicationXPHP();
        $targetType = TypeDescriptor::createFromVariable("string");
        $targetMediaType = MediaType::createApplicationJson();

        $expectedResult = '{"name":"John","age":30}';
        $actualResult = $this->sut->convert($source, $sourceType, $sourceMediaType, $targetType, $targetMediaType);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function testCannotCovertObjectWithPrivateProperties()
    {
        $source = new MessageStub('John', 30, 'private property');
        $sourceType = TypeDescriptor::createFromVariable($source);
        $sourceMediaType = MediaType::createApplicationXPHP();
        $targetType = TypeDescriptor::createFromVariable("string");
        $targetMediaType = MediaType::createApplicationJson();
        $result = $this->sut->convert($source, $sourceType, $sourceMediaType, $targetType, $targetMediaType);
        $expected = '{"name":"John","age":30}';
        $this->assertEquals($expected, $result);
    }

    public function testConvertArray()
    {
        $source = ["name" => "John", "age" => 30];
        $sourceType = TypeDescriptor::createFromVariable($source);
        $sourceMediaType = MediaType::createApplicationXPHP();
        $targetType = TypeDescriptor::createFromVariable("string");
        $targetMediaType = MediaType::createApplicationJson();

        $expectedResult = '{"name":"John","age":30}';
        $actualResult = $this->sut->convert($source, $sourceType, $sourceMediaType, $targetType, $targetMediaType);

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function testConvertString()
    {
        $source = '{"name":"John","age":30}';
        $sourceType = TypeDescriptor::createFromVariable($source);
        $sourceMediaType = MediaType::createApplicationXPHP();
        $targetType = TypeDescriptor::createFromVariable("string");
        $targetMediaType = MediaType::createApplicationJson();

        $expectedResult = '"{\"name\":\"John\",\"age\":30}"';
        $actualResult = $this->sut->convert($source, $sourceType, $sourceMediaType, $targetType, $targetMediaType);

        $this->assertEquals($expectedResult, $actualResult);
    }
}
