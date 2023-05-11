<?php

declare(strict_types=1);

namespace App\Application\Handlers;

use App\Application\Commands\CreateOrderCommand;
use App\Domain\{AbstractOrderRepository, AbstractProductRepository, Order, OrderCreated};
use Core\Application\Handlers\{AbstractCommandHandler, CommandHandlerInterface};
use Core\Application\{ApplicationException, MessageDispatcherInterface, Result};
use Core\Domain\Validators\AbstractValidator;
use Exception;

/**
 * Class CreateOrderHandler.
 *
 * @author Mateus Macedo Dos Anjos <macedodosanjosmateus@gmail.com>
 *
 * @version 0.0.1
 */
class CreateOrderHandler implements CommandHandlerInterface
{
    /**
     * CreateOrderHandler constructor.
     *
     * @param AbstractValidator          $validator
     * @param AbstractOrderRepository    $orderRepository
     * @param AbstractProductRepository  $productRepository
     * @param MessageDispatcherInterface $messageDispatcher
     */
    public function __construct(
        private readonly AbstractValidator $validator,
        private readonly AbstractOrderRepository $orderRepository,
        private readonly AbstractProductRepository $productRepository,
        private readonly MessageDispatcherInterface $messageDispatcher
    ) {
    }

    /**
     * Handle a create order command.
     *
     * @param CreateOrderCommand $command
     *
     * @return Result
     */
    public function handle($command): Result
    {
        try {
            $products = $this->productRepository->findByIds($command->products);
            $data = [
                'id' => $command->id,
                'products' => $products,
            ];

            if (!$this->validator->validate($data)) {
                return Result::failure(
                    new ApplicationException(
                        $this->validator->getError()->getMessage()
                    )
                );
            }

            $order = Order::create($data);
            $order = $this->orderRepository->save($order);

            foreach ($order->getEvents() as $event) {
                $this->messageDispatcher->dispatch($event);
            }
            $order->clearEvents();
            return Result::success($order);
        } catch (Exception $e) {
            return Result::failure(new ApplicationException($e->getMessage()));
        }
    }
}
