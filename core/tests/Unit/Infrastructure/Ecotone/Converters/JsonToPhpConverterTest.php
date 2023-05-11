<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Ecotone\Converters;

use Core\Infrastructure\Ecotone\Converters\JsonToPhpConverter;
use Ecotone\Messaging\Conversion\MediaType;
use Ecotone\Messaging\Handler\TypeDescriptor;
use Tests\BaseTestCase;
use Tests\Unit\Infrastructure\Ecotone\Converters\Stubs\MessageStub;


class JsonToPhpConverterTest extends BaseTestCase
{
    private JsonToPhpConverter $jsonToPhpConverter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->jsonToPhpConverter = new JsonToPhpConverter();
    }

    public function testCanConvertJsonToPhp(): void
    {
        $result = $this->jsonToPhpConverter->matches(TypeDescriptor::createFromVariable('string'), MediaType::createApplicationJson(), TypeDescriptor::createFromVariable('object'), MediaType::createApplicationXPHP());

        $this->assertTrue($result);
    }

    public function testCanNotConvertXPHPToJSON(): void
    {
        $result = $this->jsonToPhpConverter->matches(TypeDescriptor::createFromVariable('object'), MediaType::createApplicationXPHP(), TypeDescriptor::createFromVariable('string'), MediaType::createApplicationJson());

        $this->assertFalse($result);
    }

    public function testCanConvertJsonStringToObject(): void
    {
        $data = '{"name":"Test Name", "age":25}';

        $result = $this->jsonToPhpConverter->convert($data, TypeDescriptor::createFromVariable('string'), MediaType::createApplicationJson(), TypeDescriptor::createFromVariable(new MessageStub()), MediaType::createApplicationXPHP());

        $this->assertInstanceOf(MessageStub::class, $result);
        $this->assertEquals("Test Name", $result->name);
        $this->assertEquals(25, $result->age);
    }
}
