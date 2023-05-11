<?php

declare(strict_types=1);

namespace Tests\Unit\Infraestructure\Message;

use App\Infraestructure\Message\InMemoryMessageDispatcher;

use Core\Application\CommandInterface;
use Core\Application\EventInterface;
use Core\Application\QueryInterface;
use Tests\BaseTestCase;

final class InMemoryMessageDispatcherTest extends BaseTestCase
{
    private InMemoryMessageDispatcher $sut;
    private CommandInterface $command;
    private QueryInterface $query;
    private EventInterface $event;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = new InMemoryMessageDispatcher();
        $this->command = $this->getMockBuilder(CommandInterface::class)
            ->getMock();
    }

    public function testDispatch(): void
    {
        $this->sut->addHandler(get_class($this->command), function (CommandInterface $command): void {
            $this->assertEquals($this->command, $command);
        });
        $this->sut->dispatch($this->command);
    }

    public function testDispatchWithMetadata(): void
    {
        $this->sut->addHandler(get_class($this->command), function (CommandInterface $command, array $metadata): void {
            $this->assertEquals($this->command, $command);
            $this->assertEquals(['foo' => 'bar'], $metadata);
        });
        $this->sut->dispatch($this->command, ['foo' => 'bar']);
    }
}
