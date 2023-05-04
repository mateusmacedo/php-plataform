<?php

declare(strict_types=1);

namespace App\Infraestructure\Database;

use App\Domain\Product;
use App\Domain\ProductRepositoryInterface;

final class InMemoryProductRepository implements ProductRepositoryInterface
{
    private array $products = [];

    /**
     * @param Product $product
     */
    public function save(Product $product): void
    {
        $this->products[$product->getId()] = $product;
    }

    /**
     * @param string $id
     * @return Product|null
     */
    public function findById(string $id): ?Product
    {
        return $this->find($id);
    }

    /**
     * @param array $ids
     * @return array
     */
    public function findByIds(array $ids): array
    {
        return array_filter($this->products, function (Product $product) use ($ids) {
            return in_array($product->getId(), $ids);
        });
    }

    /**
     * Find an aggregate root by id.
     *
     * @param string $id
     * @return Product|null
     */
    public function find(string $id): ?Product
    {
        return $this->products[$id] ?? null;
    }
}