<?php

declare(strict_types=1);

namespace App\Application\Handlers;

use App\Application\Queries\FindProductByIdQuery;
use App\Domain\{AbstractProductRepository, ProductFetched};
use Core\Application\Handlers\QueryHandlerInterface;
use Core\Application\{ApplicationException, MessageDispatcherInterface, Result};
use Core\Domain\Validators\AbstractValidator;
use Exception;

final class FindProductByIdHandler implements QueryHandlerInterface
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
     * @param FindProductByIdQuery $query
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

            $product = $this->productRepository->findById($query->productId);

            if ($product) {
                $this->messageDispatcher->dispatch(new ProductFetched($query->productId));
                return Result::success($product);
            }

            return Result::failure(new ApplicationException('Not found'));
        } catch (Exception $e) {
            return Result::failure(new ApplicationException($e->getMessage()));
        }
    }
}
