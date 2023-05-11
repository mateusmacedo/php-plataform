<?php

declare(strict_types=1);

namespace Tests\Unit\Application;

use Core\Application\{ApplicationException, MessageFactory};
use Tests\BaseTestCase;
use Tests\Unit\Application\Stubs\{CommandStub, MessageEnumStub, QueryStub};

enum MessageEnumStubMocked: string
{
    case ACTION_1 = 'action_1';
    case ACTION_2 = 'action_2';
}

final class MessageFactoryTest extends BaseTestCase
{
    protected MessageFactory $sut;

    public function testConstructorWithCorrectParams(): void
    {
        $this->sut = new MessageFactory(MessageEnumStub::class);
        $this->assertInstanceOf(MessageFactory::class, $this->sut);
    }

    public function testConstructorWithIncorrectParams(): void
    {
        $this->expectException(ApplicationException::class);
        $this->sut = new MessageFactory('MessageEnumStub');
    }

    public function testRegisterWithCorrectParams(): void
    {
        $this->sut = new MessageFactory(MessageEnumStub::class);
        $this->sut->register(MessageEnumStubMocked::class);
        $this->assertInstanceOf(MessageFactory::class, $this->sut);
    }

    public function testRegisterWithIncorrectParams(): void
    {
        $this->expectException(ApplicationException::class);
        $this->sut = new MessageFactory(MessageEnumStub::class);
        $this->sut->register('MessageEnumStub');
    }

    public function testExistsWithCorrectParams(): void
    {
        $this->sut = new MessageFactory(MessageEnumStub::class);
        $this->assertTrue($this->sut->exists(CommandStub::class));
        $this->assertTrue($this->sut->exists(QueryStub::class));
        $this->assertFalse($this->sut->exists('action_3'));
    }

    public function testExistsWithIncorrectParams(): void
    {
        $this->sut = new MessageFactory(MessageEnumStub::class);
        $this->assertFalse($this->sut->exists('MessageEnumStub'));
    }

    public function testCreateWithCorrectParams(): void
    {
        $this->sut = new MessageFactory(MessageEnumStub::class);
        $this->assertInstanceOf(CommandStub::class, $this->sut->create(CommandStub::class, []));
        $this->assertInstanceOf(QueryStub::class, $this->sut->create(QueryStub::class, []));
    }

    public function testCreateWithIncorrectParams(): void
    {
        $this->expectException(ApplicationException::class);
        $this->sut = new MessageFactory(MessageEnumStub::class);
        $this->sut->create('MessageEnumStub', []);
    }

    public function testList(): void
    {
        $this->sut = new MessageFactory(MessageEnumStub::class);
        $this->assertCount(2, $this->sut->list());
        $this->assertEquals(
            [
                0 => CommandStub::class,
                1 => QueryStub::class,
            ],
            $this->sut->list()
        );
    }
}
