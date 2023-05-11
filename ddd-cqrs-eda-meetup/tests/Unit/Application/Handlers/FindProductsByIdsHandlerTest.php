<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Handlers;

use App\Application\Handlers\{FindDiscountForProductHandler, FindOrderByIdHandler, FindProductByIdHandler, FindProductsByIdsHandler};
use App\Application\Queries\{FindDiscountForProductQuery, FindOrderByIdQuery, FindProductByIdQuery, FindProductsByIdsQuery};
use App\Domain\{AbstractOrderRepository, AbstractProductRepository, Order, Product};
use Core\Application\MessageDispatcherInterface;
use Core\Domain\DomainException;
use Core\Domain\Validators\AbstractValidator;
use Exception;
use Tests\BaseTestCase;

final class FindProductsByIdsHandlerTest extends BaseTestCase
{
    private AbstractValidator $validator;
    private AbstractProductRepository $productRepository;
    private MessageDispatcherInterface $messageDispatcher;
    private FindProductsByIdsHandler $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validator = $this->createMock(AbstractValidator::class);
        $this->productRepository = $this->createMock(AbstractProductRepository::class);
        $this->messageDispatcher = $this->createMock(MessageDispatcherInterface::class);

        $this->sut = new FindProductsByIdsHandler(
            $this->validator,
            $this->productRepository,
            $this->messageDispatcher,
        );
    }

    public function testHandlerWithValidParams(): void
    {
        $query = new FindProductsByIdsQuery(
            productIds: ['123', '456']
        );

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->willReturn(true);

        $product = $this->getMockBuilder(Product::class)
            ->setConstructorArgs(['123', 'test', 'test', 10.0])
            ->getMock();
        $products = [$product, $product, $product];
        $this->productRepository
            ->expects($this->once())
            ->method('findByIds')
            ->willReturn($products);

        $result = $this->sut->handle($query);

        $this->assertTrue($result->isSuccess);
        $this->assertEquals($products, $result->value);
    }

    public function testHandlerWithInvalidParams(): void
    {
        $query = new FindProductsByIdsQuery(
            productIds: ['123', '456']
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
        $query = new FindProductsByIdsQuery(
            productIds: ['123', '456']
        );

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->willReturn(true);

        $this->productRepository
            ->expects($this->once())
            ->method('findByIds')
            ->willReturn([]);

        $result = $this->sut->handle($query);
        $this->assertFalse($result->isSuccess);
        $this->assertEquals('Not found', $result->error->getMessage());
    }

    public function testHandlerWithException(): void
    {
        $query = new FindProductsByIdsQuery(
            productIds: ['123', '456']
        );

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->willReturn(true);

        $this->productRepository
            ->expects($this->once())
            ->method('findByIds')
            ->willThrowException(new Exception('test'));

        $result = $this->sut->handle($query);
        $this->assertFalse($result->isSuccess);
        $this->assertEquals('test', $result->error->getMessage());
    }
}
