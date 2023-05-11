<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Domain\Product;
use Tests\BaseTestCase;

final class ProductTest extends BaseTestCase
{
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = Product::create([
            'id' => '1',
            'name' => 'Product 1',
            'description' => 'Description of product 1',
            'price' => 100.00,
        ]);
    }

    public function testGetName(): void
    {
        $this->assertEquals('Product 1', $this->product->getName());
    }

    public function testGetDescription(): void
    {
        $this->assertEquals('Description of product 1', $this->product->getDescription());
    }

    public function testGetPrice(): void
    {
        $this->assertEquals(100.00, $this->product->getPrice());
    }

    public function testApplyDiscount(): void
    {
        $this->product->applyDiscount(0.1);

        $this->assertEquals(90.00, $this->product->getPrice());
    }
}
