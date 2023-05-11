<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Domain\Order;
use App\Domain\Product;
use Core\Domain\DomainException;
use Tests\BaseTestCase;

final class OrderTest extends BaseTestCase
{
    private Order $sut;
    private Product $productOne;
    private Product $productTwo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productOne = $this->getMockBuilder(Product::class)
            ->setConstructorArgs([
                'id' => '1',
                'name' => 'Product 1',
                'description' => 'Description of product 1',
                'price' => 100.00,
            ])
            ->getMock();
        $this->productTwo = $this->getMockBuilder(Product::class)
            ->setConstructorArgs([
                'id' => '2',
                'name' => 'Product 2',
                'description' => 'Description of product 2',
                'price' => 200.00,
            ])
            ->getMock();

        $this->sut = Order::create([
            'id' => '1'
        ]);
    }

    public function testConstructorWithProducts(): void
    {
        $this->sut = Order::create([
            'id' => '1',
            'products' => [
                $this->productOne,
                $this->productTwo
            ]
        ]);
        $products = [];
        foreach ($this->sut->getProducts() as $product) {
            $products[] = $product;
        }
        $this->assertCount(2, $products);
    }

    public function testConstructorWithInvalidProduct(): void
    {
        $this->expectException(DomainException::class);
        $this->sut = Order::create([
            'id' => '1',
            'products' => [
                'invalid product'
            ]
        ]);
    }

    public function testAddProduct(): void
    {
        $this->sut->addProduct($this->productOne);
        $this->sut->addProduct($this->productTwo);
        $products = [];
        foreach ($this->sut->getProducts() as $product) {
            $products[] = $product;
        }
        $this->assertCount(2, $products);
    }

    public function testAddProductWithSameProduct(): void
    {
        $this->expectException(DomainException::class);
        $this->sut->addProduct($this->productOne);
        $this->sut->addProduct($this->productOne);
    }

    public function testGetProduct(): void
    {
        $this->sut->addProduct($this->productOne);
        $this->sut->addProduct($this->productTwo);
        $product = $this->sut->getProduct($this->productOne);
        $this->assertEquals($this->productOne, $product);
        $product = $this->sut->getProduct($this->productTwo);
        $this->assertEquals($this->productTwo, $product);
    }

    public function testGetProductWithInvalidProduct(): void
    {
        $this->expectException(DomainException::class);
        $this->sut->getProduct($this->productOne);
    }

    public function testRemoveProduct(): void
    {
        $this->sut->addProduct($this->productOne);
        $this->sut->addProduct($this->productTwo);
        $this->sut->removeProduct($this->productOne);
        $products = [];
        foreach ($this->sut->getProducts() as $product) {
            $products[] = $product;
        }
        $this->assertCount(1, $products);
    }

    public function testRemoveProductWithInvalidProduct(): void
    {
        $this->expectException(DomainException::class);
        $this->sut->removeProduct($this->productOne);
    }
}
