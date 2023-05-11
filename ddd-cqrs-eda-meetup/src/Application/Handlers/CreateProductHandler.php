<?php

declare(strict_types=1);

namespace App\Application\Handlers;

use App\Application\Commands\CreateProductCommand;
use App\Domain\{AbstractProductRepository, Product, ProductCreated};
use Core\Application\Handlers\{AbstractCommandHandler, CommandHandlerInterface};
use Core\Application\{ApplicationException, MessageDispatcherInterface, Result};
use Core\Domain\Validators\AbstractValidator;
use Exception;

final class CreateProductHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly AbstractValidator $validator,
        private readonly AbstractProductRepository $productRepository,
        private readonly MessageDispatcherInterface $messageDispatcher
    ) {
    }

    /**
     * Handle a message and return a result.
     *
     * @param CreateProductCommand $command
     *
     * @return Result
     */
    public function handle($command): Result
    {
        try {
            $data = (array) $command;
            if (!$this->validator->validate($data)) {
                return Result::failure(
                    new ApplicationException(
                        $this->validator->getError()->getMessage()
                    )
                );
            }
            $product = Product::create($data);
            $product = $this->productRepository->save($product);
            foreach ($product->getEvents() as $event) {
                $this->messageDispatcher->dispatch($event);
            }
            $product->clearEvents();
            return Result::success($product);
        } catch (Exception $e) {
            return Result::failure(new ApplicationException($e->getMessage()));
        }
    }
}
