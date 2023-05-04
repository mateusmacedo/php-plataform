<?php

declare(strict_types=1);

namespace App\Domain;

use Core\Domain\AbstractAggregateRoot;
use Core\Domain\FactoryMethodInterface;
use DateTimeImmutable;

class Product extends AbstractAggregateRoot implements FactoryMethodInterface
{
    public function __construct(
        ?string $id,
        protected ?string $name = null,
        protected ?string $description = null,
        protected ?float $price = null,
        ?string $createdAt = null,
        ?string $updatedAt = null,
        ?string $deletedAt = null,
    ) {
        parent::__construct($id, $createdAt, $updatedAt, $deletedAt);
    }

    public function applyDiscount(float $discount): void
    {
        $priceWithDiscount = $this->price - ($this->price * $discount);
        $now = (new DateTimeImmutable())->format(DateTimeImmutable::ISO8601);

        $this->price = $priceWithDiscount;
        $this->updatedAt = $now;

        $this->record(new ProductDiscountApplied($discount, $this->id));
    }

    /**
     * Method to create a new instance of the class
     *
     * @param mixed $data
     * @return mixed
     */
    public static function create($data)
    {
        $now = (new DateTimeImmutable())->format(DateTimeImmutable::ISO8601);
        return new self(
            $data['id'] ?? null,
            $data['name'] ?? null,
            $data['description'] ?? null,
            $data['price'] ?? null,
            $now,
            $now
        );
    }
}