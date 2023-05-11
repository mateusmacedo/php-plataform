<?php

declare(strict_types=1);

namespace Tests\Unit\Infraestructure\Database;

use App\Domain\Order;

use App\Domain\Product;

use App\Infraestructure\Database\InMemoryOrderRepository;

use Tests\BaseTestCase;

final class InMemoryOrderRepositoryTest extends BaseTestCase
{
    private InMemoryOrderRepository $sut;
    private Order $order;
    private Product $productOne;
    private Product $productTwo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = new InMemoryOrderRepository();
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
        $this->order = Order::create([
            'id' => '1',
            'products' => [
                $this->productOne,
                $this->productTwo
            ]
        ]);
    }

    public function testFind(): void
    {
        $this->sut->save($this->order);
        $this->assertEquals($this->order, $this->sut->find('1'));
    }

    public function testFindUnexisting(): void
    {
        $this->assertNull($this->sut->find('1'));
    }

    public function testFindById(): void
    {
        $this->sut->save($this->order);
        $this->assertEquals($this->order, $this->sut->findById('1'));
    }

    public function testFindByIdUnexisting(): void
    {
        $this->assertNull($this->sut->findById('1'));
    }

    public function testSave(): void
    {
        $this->sut->save($this->order);
        $this->assertEquals($this->order, $this->sut->find('1'));
    }
}
