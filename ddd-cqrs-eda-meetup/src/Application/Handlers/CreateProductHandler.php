<?php

declare(strict_types=1);

namespace App\Application\Handlers;

use App\Application\Commands\CreateProductCommand;
use App\Domain\Product;
use App\Domain\ProductCreated;
use App\Domain\ProductRepositoryInterface;
use Core\Application\ApplicationException;
use Core\Application\Handlers\CommandHandlerInterface;
use Core\Application\MessageDispatcherInterface;
use Core\Application\Result;
use Core\Domain\Validators\AbstractValidator;
use Exception;

final class CreateProductHandler implements CommandHandlerInterface
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
	 * @param CreateProductCommand $command
	 * @return Result
	 */
	public function handle(CreateProductCommand $command): Result
	{
		try {
			$data = (array) $command;
			if (!$this->validator->validate($data)) {
				return Result::failure(
                    new ApplicationException(
                        $this->validator->getError()->getMessage()
                    )
                );
			}
			$product = Product::create($data);
			$this->productRepository->save($product);
			$this->messageDispatcher->dispatch(
				new ProductCreated(
					$product->name,
					$product->description,
					$product->price,
					$product->id
				)
			);
			return Result::success($product);
		} catch (Exception $e) {
			return Result::failure(new ApplicationException($e->getMessage()));
		}
	}
}