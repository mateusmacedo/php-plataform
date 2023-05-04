<?php

declare(strict_types=1);

namespace App\Application\Handlers;

use App\Application\Commands\ApplyDiscountToProductCommand;
use App\Application\FindDiscountForProductQuery;
use App\Domain\ProductRepositoryInterface;
use Core\Application\ApplicationException;
use Core\Application\Handlers\QueryHandlerInterface;
use Core\Application\MessageDispatcherInterface;
use Core\Application\Result;
use Core\Domain\Validators\AbstractValidator;
use Exception;

final class FindDiscountForProductHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly AbstractValidator $validator,
        private readonly ProductRepositoryInterface $productRepository,
        private readonly MessageDispatcherInterface $messageDispatcher
    ) {
    }
    /**
     * Handle a message and return a result
     *
     * @param FindDiscountForProductQuery $query
     * @return Result
     */
    public function handle(FindDiscountForProductQuery $query): Result
    {
        try {
            if (!$this->validator->validate($query)) {
                return Result::failure(
                    new ApplicationException(
                        $this->validator->getError()->getMessage()
                    )
                );
            }

            $product = $this->productRepository->findById($query->productId);

            if ($product) {
                return Result::success(0.1);
            }

            return Result::failure(new ApplicationException('Could not find discount for product'));
        } catch (Exception $e) {
            return Result::failure(new ApplicationException($e->getMessage()));
        }
    }
}