<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Handlers;

use App\Application\Commands\AddProductToOrderCommand;
use App\Application\Handlers\AddProductToOrderHandler;
use App\Domain\{AbstractOrderRepository, AbstractProductRepository, Order, Product};
use Core\Application\{ApplicationException, EventInterface, MessageDispatcherInterface, Result};
use Core\Domain\Validators\AbstractValidator;
use Exception;
use Tests\BaseTestCase;

class AddProductToOrderHandlerTest extends BaseTestCase
{
    private AbstractValidator $validator;
    private AbstractOrderRepository $orderRepository;
    private AbstractProductRepository $productRepository;
    private MessageDispatcherInterface $messageDispatcher;
    private AddProductToOrderHandler $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validator = $this->createMock(AbstractValidator::class);
        $this->orderRepository = $this->createMock(AbstractOrderRepository::class);
        $this->productRepository = $this->createMock(AbstractProductRepository::class);
        $this->messageDispatcher = $this->createMock(MessageDispatcherInterface::class);

        $this->sut = new AddProductToOrderHandler(
            $this->validator,
            $this->orderRepository,
            $this->productRepository,
            $this->messageDispatcher
        );
    }

    public function testHandleWithExistentProductAndOrderWithValidParams(): void
    {
        $command = new AddProductToOrderCommand('123', '123');

        $this->validator->method('validate')
            ->with($command)
            ->willReturn(true);

        $order = $this->createMock(Order::class);
        $product = $this->createMock(Product::class);

        $this->orderRepository->method('findById')
            ->with($command->orderId)
            ->willReturn($order);

        $this->productRepository->method('findById')
            ->with($command->productId)
            ->willReturn($product);

        $order->expects($this->once())
            ->method('addProduct')
            ->with($product);

        $this->orderRepository->expects($this->once())
            ->method('save')
            ->with($order);

        $event = $this->createMock(EventInterface::class);
        $mockedGenerator = function () use ($event) {
            yield $event;
        };
        $order->expects($this->once())
            ->method('getEvents')
            ->will($this->returnCallback($mockedGenerator));

        $this->messageDispatcher->expects($this->once())
            ->method('dispatch')
            ->with($event);

        $order->expects($this->once())
            ->method('clearEvents');

        $result = $this->sut->handle($command);

        $this->assertInstanceOf(Result::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertSame($order, $result->value);
    }

    public function testHandleWithExistentProductAndOrderWithInvalidParams(): void
    {
        $command = new AddProductToOrderCommand('123', '123');

        $this->validator->method('validate')
            ->with($command)
            ->willReturn(false);
        $this->validator->method('getError')
            ->with(new Exception('Invalid params'));

        $result = $this->sut->handle($command);

        $this->assertInstanceOf(Result::class, $result);
        $this->assertFalse($result->isSuccess());
        $this->assertInstanceOf(
            ApplicationException::class,
            $result->error
        );
    }

    public function testHandleWithNonExistentProduct(): void
    {
        $command = new AddProductToOrderCommand('123', '123');

        $this->validator->method('validate')
            ->with($command)
            ->willReturn(true);

        $this->orderRepository->method('findById')
            ->with($command->orderId)
            ->willReturn($this->createMock(Order::class));

        $this->productRepository->method('findById')
            ->with($command->productId)
            ->willReturn(null);

        $result = $this->sut->handle($command);

        $this->assertInstanceOf(Result::class, $result);
        $this->assertFalse($result->isSuccess());
        $this->assertInstanceOf(
            ApplicationException::class,
            $result->error
        );
    }

    public function testHandleWithNonExistentOrder(): void
    {
        $command = new AddProductToOrderCommand('123', '123');

        $this->validator->method('validate')
            ->with($command)
            ->willReturn(true);

        $this->orderRepository->method('findById')
            ->with($command->orderId)
            ->willReturn(null);

        $this->productRepository->method('findById')
            ->with($command->productId)
            ->willReturn($this->createMock(Product::class));

        $result = $this->sut->handle($command);

        $this->assertInstanceOf(Result::class, $result);
        $this->assertFalse($result->isSuccess());
        $this->assertInstanceOf(
            ApplicationException::class,
            $result->error
        );
    }
}
