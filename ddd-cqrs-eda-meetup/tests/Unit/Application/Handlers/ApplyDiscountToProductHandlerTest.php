<?php

declare(strict_types=1);

namespace Tests\Application\Handlers;

use App\Application\Commands\ApplyDiscountToProductCommand;
use App\Application\Handlers\ApplyDiscountToProductHandler;
use App\Domain\{AbstractProductRepository, Product};
use Core\Application\{ApplicationException, EventInterface, MessageDispatcherInterface, Result};
use Core\Domain\Validators\AbstractValidator;
use Exception;
use Tests\BaseTestCase;

class ApplyDiscountToProductHandlerTest extends BaseTestCase
{
    private AbstractValidator $validator;
    private MessageDispatcherInterface $messageDispatcher;
    private AbstractProductRepository $productRepository;
    private ApplyDiscountToProductHandler $sut;

    protected function setUp(): void
    {
        parent::setUp();

        $this->validator = $this->createMock(AbstractValidator::class);
        $this->messageDispatcher = $this->createMock(MessageDispatcherInterface::class);
        $this->productRepository = $this->createMock(AbstractProductRepository::class);

        $this->sut = new ApplyDiscountToProductHandler(
            $this->validator,
            $this->productRepository,
            $this->messageDispatcher
        );
    }

    public function testHandlerWithExistentProductWithValidParams(): void
    {
        $command = new ApplyDiscountToProductCommand(0.05, '123');

        $this->validator->method('validate')
            ->with($command)
            ->willReturn(true);

        $product = $this->createMock(Product::class);

        $this->productRepository->method('findById')
            ->with($command->productId)
            ->willReturn($product);

        $product->expects($this->once())
            ->method('applyDiscount')
            ->with($command->discount);

        $this->productRepository->expects($this->once())
            ->method('save')
            ->with($product);

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

        $this->assertTrue($result->isSuccess());
        $this->assertEquals($product, $result->value);
    }

    public function testHandlerWithNonExistentProduct(): void
    {
        $command = new ApplyDiscountToProductCommand(0.05, '123');

        $this->validator->method('validate')
            ->with($command)
            ->willReturn(true);

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

    public function testHandlerWithInvalidParams(): void
    {
        $command = new ApplyDiscountToProductCommand(0.05, '123');

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
}
