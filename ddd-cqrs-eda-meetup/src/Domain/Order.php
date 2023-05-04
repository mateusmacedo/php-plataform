<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\ProductAddedToOrder;
use App\Domain\ProductRemovedFromOrder;
use Core\Domain\AbstractAggregateRoot;
use Core\Domain\DomainException;
use Core\Domain\FactoryMethodInterface;
use DateTimeImmutable;
use Ds\Map;
use Generator;

/**
 * Class Order
 *
 * @package App\Domain
 * @author Mateus Macedo Dos Anjos <macedodosanjosmateus@gmail.com>
 * @version 0.0.1
 */
final class Order extends AbstractAggregateRoot implements FactoryMethodInterface
{
    private Map $products;

    /**
     * Order constructor.
     *
     * @param string|null $id
     * @param string|null $createdAt
     * @param string|null $updatedAt
     * @param string|null $deletedAt
     * @param array $products
     * @throws DomainException
     */
    public function __construct(
        ?string $id = null,
        ?string $createdAt = null,
        ?string $updatedAt = null,
        ?string $deletedAt = null,
        array $products = []
    ) {
        // TODO: In the future, think about moving this logic to a another block of code
        $products = new Map();
        foreach ($products as $product) {
            if (!$product instanceof Product) {
                throw new DomainException('Invalid product');
            }
            $products->put($product->id, $product);
        }

        parent::__construct($id, $createdAt, $updatedAt, $deletedAt);
        $this->products = $products;
    }

    /**
     * Method to add a product to the order
     *
     * @param Product $product
     * @throws DomainException
     */
    public function addProduct(Product $product): void
    {
        if ($this->products->hasKey($product->id)) {
            throw new DomainException('Product already exists in order');
        }

        $this->products->put($product->id, $product);
        $this->record(new ProductAddedToOrder($product->id, $this->id));
    }

    /**
     * Method to get a product from the order
     *
     * @param Product $product
     * @return Product
     * @throws DomainException
     */
    public function getProduct(Product $product): Product
    {
        $result = $this->products->get($product->id);
        if (!$result) {
            throw new DomainException('Product does not exist in order');
        }
        return $result;
    }

    /**
     * Method to remove a product from the order
     *
     * @param Product $product
     * @throws DomainException
     */
    public function removeProduct(Product $product): void
    {
        if (!$this->products->get($product->id)) {
            throw new DomainException('Product does not exist in order');
        }

        $this->products->remove($product->id);
        $this->record(new ProductRemovedFromOrder($product->id, $this->id));
    }

    /**
     * Method to get all products from the order
     *
     * @return Generator
     */
    public function getProducts(): Generator
    {
        foreach ($this->products as $product) {
            yield $product;
        }
    }

    public function getProductList(): array
    {
        return $this->products->keys()->toArray();
    }

    /**
     * Method to create a new instance of the Order class
     *
     * @param array $data
     * @return Order
     */
    public static function create(array $data): Order
    {
        $now = (new DateTimeImmutable())->format(DateTimeImmutable::ISO8601);
        return new Order(
            $data['id'] ?? null,
            $data['createdAt'] ?? $now,
            $data['updatedAt'] ?? $now,
            $data['deletedAt'] ?? null,
            $data['products'] ?? []
        );
    }
}