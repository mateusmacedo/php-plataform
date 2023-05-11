<?php

declare(strict_types=1);

namespace App\Application\Handlers;

use App\Application\Commands\AddProductToOrderCommand;
use App\Domain\{AbstractOrderRepository, AbstractProductRepository};
use Core\Application\Handlers\CommandHandlerInterface;
use Core\Application\{ApplicationException, MessageDispatcherInterface, Result};
use Core\Domain\Validators\AbstractValidator;
use Exception;

class AddProductToOrderHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly AbstractValidator $validator,
        private readonly AbstractOrderRepository $orderRepository,
        private readonly AbstractProductRepository $productRepository,
        private readonly MessageDispatcherInterface $messageDispatcher
    ) {
    }

    /**
     * Handle a message and return a result.
     *
     * @param AddProductToOrderCommand $command
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

            $order = $this->orderRepository->findById($command->orderId);
            $product = $this->productRepository->findById($command->productId);

            if ($order && $product) {
                $order->addProduct($product);
                $this->orderRepository->save($order);
                foreach ($order->getEvents() as $event) {
                    $this->messageDispatcher->dispatch($event);
                }
                $order->clearEvents();
                return Result::success($order);
            }
            return Result::failure(new ApplicationException('Could not add product to order'));
        } catch (Exception $e) {
            return Result::failure(new ApplicationException($e->getMessage()));
        }
    }
}
