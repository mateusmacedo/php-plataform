<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Handlers;

use App\Application\Handlers\{FindDiscountForProductHandler, FindOrderByIdHandler, FindProductByIdHandler};
use App\Application\Queries\{FindDiscountForProductQuery, FindOrderByIdQuery, FindProductByIdQuery};
use App\Domain\{AbstractOrderRepository, AbstractProductRepository, Order, Product};
use Core\Application\MessageDispatcherInterface;
use Core\Domain\DomainException;
use Core\Domain\Validators\AbstractValidator;
use Exception;
use Tests\BaseTestCase;

final class FindProductByIdHandlerTest extends BaseTestCase
{
    private AbstractValidator $validator;
    private AbstractProductRepository $productRepository;
    private MessageDispatcherInterface $messageDispatcher;
    private FindProductByIdHandler $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validator = $this->createMock(AbstractValidator::class);
        $this->productRepository = $this->createMock(AbstractProductRepository::class);
        $this->messageDispatcher = $this->createMock(MessageDispatcherInterface::class);

        $this->sut = new FindProductByIdHandler(
            $this->validator,
            $this->productRepository,
            $this->messageDispatcher,
        );
    }

    public function testHandlerWithValidParams(): void
    {
        $query = new FindProductByIdQuery(
            productId: '123'
        );

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->willReturn(true);

        $order = $this->createMock(Product::class);
        $this->productRepository
            ->expects($this->once())
            ->method('findById')
            ->willReturn($order);

        $result = $this->sut->handle($query);

        $this->assertTrue($result->isSuccess);
        $this->assertEquals($order, $result->value);
    }

    public function testHandlerWithInvalidParams(): void
    {
        $query = new FindProductByIdQuery(
            productId: '123'
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
        $query = new FindProductByIdQuery(
            productId: '123'
        );

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->willReturn(true);

        $this->productRepository
            ->expects($this->once())
            ->method('findById')
            ->willReturn(null);

        $result = $this->sut->handle($query);
        $this->assertFalse($result->isSuccess);
        $this->assertEquals('Not found', $result->error->getMessage());
    }

    public function testHandlerWithException(): void
    {
        $query = new FindProductByIdQuery(
            productId: '123'
        );

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->willReturn(true);

        $this->productRepository
            ->expects($this->once())
            ->method('findById')
            ->willThrowException(new Exception('test'));

        $result = $this->sut->handle($query);
        $this->assertFalse($result->isSuccess);
        $this->assertEquals('test', $result->error->getMessage());
    }
}
