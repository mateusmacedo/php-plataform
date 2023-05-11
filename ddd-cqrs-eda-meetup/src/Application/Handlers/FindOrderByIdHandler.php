<?php

declare(strict_types=1);

namespace App\Application\Handlers;

use App\Application\Queries\FindOrderByIdQuery;
use App\Domain\{AbstractOrderRepository, OrderFetched};
use Core\Application\Handlers\{AbstractQueryHandler, CommandHandlerInterface};
use Core\Application\{ApplicationException, MessageDispatcherInterface, Result};
use Core\Domain\Validators\AbstractValidator;
use Exception;

final class FindOrderByIdHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly AbstractValidator $validator,
        private readonly AbstractOrderRepository $orderRepository,
        private readonly MessageDispatcherInterface $messageDispatcher
    ) {
    }

    /**
     * Handle a message and return a result.
     *
     * @param FindOrderByIdQuery $query
     *
     * @return Result
     */
    public function handle($query): Result
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
                $this->messageDispatcher->dispatch(new OrderFetched($query->orderId));
                return Result::success($order);
            }

            return Result::failure(new ApplicationException('Not found'));
        } catch (Exception $e) {
            return Result::failure(new ApplicationException($e->getMessage()));
        }
    }
}
