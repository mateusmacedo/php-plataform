<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\{ProductAddedToOrder, ProductRemovedFromOrder};
use Core\Domain\{AbstractAggregateRoot, DomainException, FactoryMethodInterface};
use DateTimeImmutable;
use Ds\Map;
use Generator;

/**
 * Class Order.
 *
 * @author Mateus Macedo Dos Anjos <macedodosanjosmateus@gmail.com>
 *
 * @version 0.0.1
 */
class Order extends AbstractAggregateRoot implements FactoryMethodInterface
{
    private Map $products;

    /**
     * Order constructor.
     *
     * @param null|string $id
     * @param null|string $createdAt
     * @param null|string $updatedAt
     * @param null|string $deletedAt
     * @param array       $products
     *
     * @throws DomainException
     */
    public function __construct(
        ?string $id = null,
        ?string $createdAt = null,
        ?string $updatedAt = null,
        ?string $deletedAt = null,
        array $products = []
    ) {
        parent::__construct($id, $createdAt, $updatedAt, $deletedAt);
        $this->products = new Map();
        // TODO: In the future, think about moving this logic to a another block of code
        foreach ($products as $product) {
            if (!$product instanceof Product) {
                throw new DomainException('Invalid product');
            }
            $this->products->put($product->id, $product);
        }
    }

    /**
     * Method to add a product to the order.
     *
     * @param Product $product
     *
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
     * Method to get a product from the order.
     *
     * @param Product $product
     *
     * @return Product
     *
     * @throws DomainException
     */
    public function getProduct(Product $product): Product
    {
        $result = $this->products->hasKey($product->id);
        if (!$result) {
            throw new DomainException('Product does not exist in order');
        }
        return $this->products->get($product->id);
    }

    /**
     * Method to remove a product from the order.
     *
     * @param Product $product
     *
     * @throws DomainException
     */
    public function removeProduct(Product $product): void
    {
        if (!$this->products->hasKey($product->id)) {
            throw new DomainException('Product does not exist in order');
        }

        $this->products->remove($product->id);
        $this->record(new ProductRemovedFromOrder($product->id, $this->id));
    }

    /**
     * Method to get all products from the order.
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
     * Method to create a new instance of the Order class.
     *
     * @param array $data
     *
     * @return Order
     */
    public static function create($data = null): Order
    {
        $now = (new DateTimeImmutable())->format(DateTimeImmutable::ISO8601);
        $order = new Order(
            $data['id'] ?? null,
            $data['createdAt'] ?? $now,
            $data['updatedAt'] ?? $now,
            $data['deletedAt'] ?? null,
            $data['products'] ?? []
        );
        $order->record(new OrderCreated($order->getProductList(), $order->id));
        return $order;
    }
}
