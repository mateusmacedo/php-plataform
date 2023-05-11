<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Handlers;

use App\Application\Commands\CreateOrderCommand;
use App\Application\Handlers\CreateOrderHandler;
use App\Domain\{AbstractOrderRepository, AbstractProductRepository, Order, Product};
use Core\Application\{ApplicationException, EventInterface, MessageDispatcherInterface, Result};
use Core\Domain\Validators\AbstractValidator;
use Exception;
use PHPUnit\Framework\Attributes\{PreserveGlobalState, RunInSeparateProcess};
use Tests\BaseTestCase;

final class CreateOrderHandlerTest extends BaseTestCase
{
    private AbstractValidator $validator;
    private MessageDispatcherInterface $messageDispatcher;
    private AbstractOrderRepository $orderRepository;
    private AbstractProductRepository $productRepository;
    private CreateOrderHandler $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validator = $this->createMock(AbstractValidator::class);
        $this->messageDispatcher = $this->createMock(MessageDispatcherInterface::class);
        $this->orderRepository = $this->createMock(AbstractOrderRepository::class);
        $this->productRepository = $this->createMock(AbstractProductRepository::class);

        $this->sut = new CreateOrderHandler(
            $this->validator,
            $this->orderRepository,
            $this->productRepository,
            $this->messageDispatcher,
        );
    }

    public function testHandlerWithValidParamsWithoutProducts(): void
    {
        $command = new CreateOrderCommand(
            id: '123',
            products: [],
        );

        $this->validator->method('validate')
            ->with(['id' => $command->id, 'products' => $command->products])
            ->willReturn(true);

        $order = $this->getMockBuilder(Order::class)
            ->setConstructorArgs([
                'id' => '123',
                'products' => [],
            ])
            ->getMock();
        $this->orderRepository->expects($this->once())
            ->method('save')
            ->willReturn($order);

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
        $this->assertTrue($result->isSuccess);
        $this->assertEquals($order, $result->value);
    }

    public function testHandlerWithValidParamsWithProducts(): void
    {
        $command = new CreateOrderCommand(
            id: '123',
            products: ['123', '456'],
        );

        $this->productRepository->expects($this->once())
            ->method('findByIds')
            ->with($command->products)
            ->willReturn([
                $this->getMockBuilder(Product::class)
                    ->setConstructorArgs([
                        'id' => '1',
                        'name' => 'Product 1',
                        'description' => 'Description of product 1',
                        'price' => 100.00,
                    ])
                    ->getMock(),
                $this->getMockBuilder(Product::class)
                    ->setConstructorArgs([
                        'id' => '2',
                        'name' => 'Product 2',
                        'description' => 'Description of product 2',
                        'price' => 200.00,
                    ])
                    ->getMock()
                ,
            ]);

        $this->validator->method('validate')
            ->willReturn(true);

        $order = $this->createMock(Order::class);
        $this->orderRepository->expects($this->once())
            ->method('save')
            ->willReturn($order);

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
        $this->assertTrue($result->isSuccess);
        $this->assertEquals($order, $result->value);
    }

    public function testHandlerWithInvalidParams(): void
    {
        $command = new CreateOrderCommand(
            id: '123',
            products: [],
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
