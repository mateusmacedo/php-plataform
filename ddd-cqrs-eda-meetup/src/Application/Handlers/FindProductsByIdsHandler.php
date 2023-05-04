<?php

declare(strict_types=1);

namespace App\Application\Handlers;

use App\Application\Queries\FindProductsByIdsQuery;
use App\Domain\ProductFetched;
use App\Domain\ProductRepositoryInterface;
use Core\Application\ApplicationException;
use Core\Application\Handlers\QueryHandlerInterface;
use Core\Application\MessageDispatcherInterface;
use Core\Application\Result;
use Core\Domain\Validators\AbstractValidator;
use Exception;

final class FindProductsByIdsHandler implements QueryHandlerInterface
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
     * @param FindProductsByIdsQuery $query
     * @return Result
     */
    public function handle(FindProductsByIdsQuery $query): Result
    {
        try {
            if (!$this->validator->validate($query)) {
                return Result::failure(
                    new ApplicationException(
                        $this->validator->getError()->getMessage()
                    )
                );
            }

            $products = $this->productRepository->findByIds($query->productIds);

            if (count($products) > 0) {
                foreach ($products as $product) {
                    $this->messageDispatcher->dispatch(new ProductFetched($product->id));
                }
                return Result::success($products);
            }

            return Result::failure(new ApplicationException('Could not find any product'));
        } catch (Exception $e) {
            return Result::failure(new ApplicationException($e->getMessage()));
        }
    }
}