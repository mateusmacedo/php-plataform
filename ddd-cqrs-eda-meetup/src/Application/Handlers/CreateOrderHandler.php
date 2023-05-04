<?php

declare(strict_types=1);

namespace App\Application;

use App\Application\CreateOrderCommand;
use App\Domain\Order;
use App\Domain\OrderCreated;
use App\Domain\OrderRepositoryInterface;
use App\Domain\ProductRepositoryInterface;
use Core\Application\ApplicationException;
use Core\Application\Handlers\CommandHandlerInterface;
use Core\Application\MessageDispatcherInterface;
use Core\Application\Result;
use Core\Domain\Validators\AbstractValidator;
use Exception;

/**
 * Class CreateOrderHandler
 *
 * @package App\Application
 * @author Mateus Macedo Dos Anjos <macedodosanjosmateus@gmail.com>
 * @version 0.0.1
 */
class CreateOrderHandler implements CommandHandlerInterface
{
    /**
     * CreateOrderHandler constructor
     *
     * @param AbstractValidator $validator
     * @param OrderRepositoryInterface $orderRepository
     * @param ProductRepositoryInterface $productRepository
     * @param MessageDispatcherInterface $messageDispatcher
     */
    public function __construct(
        private readonly AbstractValidator $validator,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly ProductRepositoryInterface $productRepository,
        private readonly MessageDispatcherInterface $messageDispatcher
    ) {
    }

    /**
     * Handle a create order command
     *
     * @param CreateOrderCommand $command
     * @return Result
     */
    public function handle(CreateOrderCommand $command): Result
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
            $this->orderRepository->save($order);

            $this->messageDispatcher->dispatch(new OrderCreated($order->getProductList(), $order->id));

            return Result::success($order);
        } catch (Exception $e) {
            return Result::failure(new ApplicationException($e->getMessage()));
        }
    }
}