<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Handlers;

use App\Application\Handlers\FindDiscountForProductHandler;
use App\Application\Queries\FindDiscountForProductQuery;
use App\Domain\{AbstractProductRepository, Product};
use Core\Application\MessageDispatcherInterface;
use Core\Domain\DomainException;
use Core\Domain\Validators\AbstractValidator;
use Exception;
use Tests\BaseTestCase;

final class FindDiscountForProductHandlerTest extends BaseTestCase
{
    private AbstractValidator $validator;
    private AbstractProductRepository $productRepository;
    private MessageDispatcherInterface $messageDispatcher;
    private FindDiscountForProductHandler $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validator = $this->createMock(AbstractValidator::class);
        $this->productRepository = $this->createMock(AbstractProductRepository::class);
        $this->messageDispatcher = $this->createMock(MessageDispatcherInterface::class);

        $this->sut = new FindDiscountForProductHandler(
            $this->validator,
            $this->productRepository,
            $this->messageDispatcher,
        );
    }

    public function testHandlerWithValidParams(): void
    {
        $query = new FindDiscountForProductQuery(
            productId: '123',
            discountCode: 'test',
        );

        $this->validator
            ->expects($this->once())
            ->method('validate')
            ->willReturn(true);

        $product = $this->createMock(Product::class);
        $this->productRepository
            ->expects($this->once())
            ->method('findById')
            ->willReturn($product);

        $result = $this->sut->handle($query);

        $this->assertTrue($result->isSuccess);
        $this->assertEquals(0.1, $result->value);
    }

    public function testHandlerWithInvalidParams(): void
    {
        $query = new FindDiscountForProductQuery(
            productId: '123',
            discountCode: 'test',
        );

        $this->validator->expects($this->once())
            ->method('validate')
            ->willReturn(false);
        $this->validator->expects($this->once())
            ->method('getError')
            ->willReturn(new DomainException('test'));

        $result = $this->sut->handle($query);
        $this->assertFalse($result->isSuccess);
        $this->assertEquals('test', $result->error->getMessage());
    }

    public function testHandlerWithNotFoundProduct(): void
    {
        $query = new FindDiscountForProductQuery(
            productId: '123',
            discountCode: 'test',
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
        $this->assertEquals('Could not find discount for product', $result->error->getMessage());
    }

    public function testHandlerWithException(): void
    {
        $query = new FindDiscountForProductQuery(
            productId: '123',
            discountCode: 'test',
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
