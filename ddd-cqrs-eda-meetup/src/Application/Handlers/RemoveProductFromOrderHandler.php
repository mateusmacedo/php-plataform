<?php

declare(strict_types=1);

namespace App\Application\Handlers;

use App\Application\RemoveProductFromOrderCommand;
use App\Domain\OrderRepositoryInterface;
use App\Domain\ProductRepositoryInterface;
use Core\Application\ApplicationException;
use Core\Application\Handlers\CommandHandlerInterface;
use Core\Application\MessageDispatcherInterface;
use Core\Application\Result;
use Core\Domain\Validators\AbstractValidator;
use Exception;

final class RemoveProductFromOrderHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly AbstractValidator $validator,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly ProductRepositoryInterface $productRepository,
        private readonly MessageDispatcherInterface $messageDispatcher
    ) {
    }
    /**
     * Handle a message and return a result
     *
     * @param RemoveProductFromOrderCommand $command
     * @return Result
     */
    public function handle(RemoveProductFromOrderCommand $command): Result
    {
        try {
            if (!$this->validator->validate($command)) {
                return Result::failure(
                    new ApplicationException(
                        $this->validator->getError()->getMessage()
                    )
                );
            }

            $order = $this->orderRepository->findById($command->orderId);
            $product = $this->productRepository->findById($command->productId);

            if ($order && $product) {
                $order->removeProduct($product);
                $this->orderRepository->save($order);
                foreach ($order->releaseEvents() as $event) {
                    $this->messageDispatcher->dispatch($event);
                }
                $order->clearEvents();
                return Result::success($command->orderId);
            }
            return Result::failure(new ApplicationException('Could not remove product from order'));
        } catch (Exception $e) {
            return Result::failure(new ApplicationException($e->getMessage()));
        }
    }
}