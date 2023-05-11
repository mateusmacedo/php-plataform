<?php

declare(strict_types=1);

namespace App\Application\Handlers;

use App\Application\Commands\ApplyDiscountToProductCommand;
use App\Domain\AbstractProductRepository;
use Core\Application\Handlers\CommandHandlerInterface;
use Core\Application\{ApplicationException, MessageDispatcherInterface, Result};
use Core\Domain\Validators\AbstractValidator;
use Exception;

class ApplyDiscountToProductHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly AbstractValidator $validator,
        private AbstractProductRepository $productRepository,
        private readonly MessageDispatcherInterface $messageDispatcher
    ) {
    }

    /**
     * Handle a message and return a result.
     *
     * @param ApplyDiscountToProductCommand $command
     *
     * @return Result
     */
    public function handle($command): Result
    {
        try {
            if (!$this->validator->validate($command)) {
                return Result::failure(
                    new ApplicationException(
                        $this->validator->getError()->getMessage()
                    )
                );
            }

            $product = $this->productRepository->findById($command->productId);

            if ($product) {
                $product->applyDiscount($command->discount);
                $this->productRepository->save($product);
                foreach ($product->getEvents() as $event) {
                    $this->messageDispatcher->dispatch($event);
                }
                $product->clearEvents();
                return Result::success($product);
            }

            return Result::failure(new ApplicationException('Could not apply discount to product'));
        } catch (Exception $e) {
            return Result::failure(new ApplicationException($e->getMessage()));
        }
    }
}
