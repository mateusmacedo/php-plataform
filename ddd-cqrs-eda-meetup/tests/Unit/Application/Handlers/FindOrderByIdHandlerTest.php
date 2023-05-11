<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Handlers;

use App\Application\Handlers\{FindDiscountForProductHandler, FindOrderByIdHandler};
use App\Application\Queries\{FindDiscountForProductQuery, FindOrderByIdQuery};
use App\Domain\{AbstractOrderRepository, AbstractProductRepository, Order, Product};
use Core\Application\MessageDispatcherInterface;
use Core\Domain\DomainException;
use Core\Domain\Validators\AbstractValidator;
use Exception;
use Tests\BaseTestCase;

final class FindOrderByIdHandlerTest extends BaseTestCase
{
    private AbstractValidator $validator;
    private AbstractOrderRepository $orderRepository;
    private MessageDispatcherInterface $messageDispatcher;
    private FindOrderByIdHandler $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validator = $this->createMock(AbstractValidator::class);
        $this->orderRepository = $this->createMock(AbstractOrderRepository::class);
        $this->messageDispatcher = $this->createMock(MessageDispatcherInterface::class);

        $this->sut = new FindOrderByIdHandler(
            $this->validator,
            $this->orderRepository,
            $this->messageDispatcher,
        );
    }

    public function testHandlerWithValidParams(): void
    {
        $query = new FindOrderByIdQuery(
            orderId: '123'
        );

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->willReturn(true);

        $order = $this->createMock(Order::class);
        $this->orderRepository
            ->expects($this->once())
            ->method('findById')
            ->willReturn($order);

        $result = $this->sut->handle($query);

        $this->assertTrue($result->isSuccess);
        $this->assertEquals($order, $result->value);
    }

    public function testHandlerWithInvalidParams(): void
    {
        $query = new FindOrderByIdQuery(
            orderId: '123'
        );

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->willReturn(false);
        $this->validator
            ->expects($this->once())
            ->method('getError')
            ->willReturn(new DomainException('test'));

        $result = $this->sut->handle($query);
        $this->assertFalse($result->isSuccess);
        $this->assertEquals('test', $result->error->getMessage());
    }

    public function testHandlerWithNotFoundProduct(): void
    {
        $query = new FindOrderByIdQuery(
            orderId: '123'
        );

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->willReturn(true);

        $this->orderRepository
            ->expects($this->once())
            ->method('findById')
            ->willReturn(null);

        $result = $this->sut->handle($query);
        $this->assertFalse($result->isSuccess);
        $this->assertEquals('Not found', $result->error->getMessage());
    }

    public function testHandlerWithException(): void
    {
        $query = new FindOrderByIdQuery(
            orderId: '123'
        );

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->willReturn(true);

        $this->orderRepository
            ->expects($this->once())
            ->method('findById')
            ->willThrowException(new Exception('test'));

        $result = $this->sut->handle($query);
        $this->assertFalse($result->isSuccess);
        $this->assertEquals('test', $result->error->getMessage());
    }
}
