<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Handlers;

use App\Application\Commands\{CreateOrderCommand, CreateProductCommand};
use App\Application\Handlers\{CreateOrderHandler, CreateProductHandler};
use App\Domain\{AbstractOrderRepository, AbstractProductRepository, Order, Product};
use Core\Application\{ApplicationException, EventInterface, MessageDispatcherInterface, Result};
use Core\Domain\Validators\AbstractValidator;
use Exception;
use PHPUnit\Framework\Attributes\{PreserveGlobalState, RunInSeparateProcess};
use Tests\BaseTestCase;

final class CreateProductHandlerTest extends BaseTestCase
{
    private AbstractValidator $validator;
    private MessageDispatcherInterface $messageDispatcher;
    private AbstractProductRepository $productRepository;
    private CreateProductHandler $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validator = $this->createMock(AbstractValidator::class);
        $this->messageDispatcher = $this->createMock(MessageDispatcherInterface::class);
        $this->productRepository = $this->createMock(AbstractProductRepository::class);

        $this->sut = new CreateProductHandler(
            $this->validator,
            $this->productRepository,
            $this->messageDispatcher,
        );
    }

    public function testHandlerWithValidParamsWithoutProducts(): void
    {
        $command = new CreateProductCommand(
            id: '123',
            name: 'test',
            price: 10.0,
            description: 'test',
        );

        $this->validator->method('validate')
            ->with((array) $command)
            ->willReturn(true);

        $product = $this->createMock(Product::class);
        $this->productRepository->expects($this->once())
            ->method('save')
            ->willReturn($product);

        $event = $this->createMock(EventInterface::class);
        $mockedGenerator = function () use ($event) {
            yield $event;
        };
        $product->expects($this->once())
            ->method('getEvents')
            ->will($this->returnCallback($mockedGenerator));

        $this->messageDispatcher->expects($this->once())
            ->method('dispatch')
            ->with($event);

        $product->expects($this->once())
            ->method('clearEvents');

        $result = $this->sut->handle($command);
        $this->assertTrue($result->isSuccess);
        $this->assertEquals($product, $result->value);
    }

    public function testHandlerWithInvalidParams(): void
    {
        $command = new CreateProductCommand(
            id: '123',
            name: 'test',
            price: 10.0,
            description: 'test',
        );

        $this->validator->method('validate')
            ->willReturn(false);
        $this->validator->method('getError')
            ->with(new Exception('Invalid params'));

        $result = $this->sut->handle($command);
        $this->assertInstanceOf(Result::class, $result);
        $this->assertFalse($result->isSuccess);
        $this->assertInstanceOf(
            ApplicationException::class,
            $result->error
        );
    }
}
