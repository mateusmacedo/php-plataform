<?php

declare(strict_types=1);

namespace App\Infraestructure\Database;

use App\Domain\{AbstractProductRepository, Product};

final class InMemoryProductRepository extends AbstractProductRepository
{
    private array $products = [];

    /**
     * Save an aggregate root.
     *
     * @param Product $product
     */
    public function save($product): void
    {
        $this->products[$product->id] = $product;
    }

    /**
     * @param string $id
     *
     * @return null|Product
     */
    public function find($id): ?Product
    {
        return $this->products[$id] ?? null;
    }

    /**
     * @param string $id
     *
     * @return null|Product
     */
    public function findById(string $id): ?Product
    {
        return $this->find($id);
    }

    /**
     * @param array $ids
     *
     * @return array
     */
    public function findByIds(array $ids): array
    {
        return array_filter($this->products, function (Product $product) use ($ids) {
            return in_array($product->id, $ids);
        });
    }
}
