<?php

declare(strict_types=1);

namespace App\Application\Handlers;

use App\Application\Queries\FindOrderByIdQuery;
use App\Domain\OrderFetched;
use App\Domain\OrderRepositoryInterface;
use Core\Application\ApplicationException;
use Core\Application\Handlers\QueryHandlerInterface;
use Core\Application\MessageDispatcherInterface;
use Core\Application\Result;
use Core\Domain\Validators\AbstractValidator;
use Exception;

final class FindOrderByIdHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly AbstractValidator $validator,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly MessageDispatcherInterface $messageDispatcher
    ) {
    }
    /**
     * Handle a message and return a result
     *
     * @param FindOrderByIdQuery $query
     * @return Result
     */
    public function handle(FindOrderByIdQuery $query): Result
    {
        try {
            if (!$this->validator->validate($query)) {
                return Result::failure(
                    new ApplicationException(
                        $this->validator->getError()->getMessage()
                    )
                );
            }

            $order = $this->orderRepository->findById($query->orderId);

            if ($order) {
                $this->messageDispatcher->dispatch(new OrderFetched($order->id));
                return Result::success($order);
            }

            return Result::failure(new ApplicationException('Could not find order'));
        } catch (Exception $e) {
            return Result::failure(new ApplicationException($e->getMessage()));
        }
    }
}