<?php

declare(strict_types=1);

namespace Tests\Unit\Infraestructure\Database;

use App\Domain\Order;

use App\Domain\Product;

use App\Infraestructure\Database\InMemoryOrderRepository;

use App\Infraestructure\Database\InMemoryProductRepository;
use Tests\BaseTestCase;

final class InMemoryProductRepositoryTest extends BaseTestCase
{
    private InMemoryProductRepository $sut;
    private Product $productOne;
    private Product $productTwo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sut = new InMemoryProductRepository();
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
    }

    public function testFind(): void
    {
        $this->sut->save($this->productOne);
        $this->sut->save($this->productTwo);
        $this->assertEquals($this->productOne, $this->sut->find('1'));
        $this->assertEquals($this->productTwo, $this->sut->find('2'));
    }

    public function testFindUnexisting(): void
    {
        $this->assertNull($this->sut->find('1'));
    }

    public function testFindById(): void
    {
        $this->sut->save($this->productOne);
        $this->sut->save($this->productTwo);
        $this->assertEquals($this->productOne, $this->sut->findById('1'));
        $this->assertEquals($this->productTwo, $this->sut->findById('2'));
    }

    public function testFindByIdUnexisting(): void
    {
        $this->assertNull($this->sut->findById('1'));
    }

    public function testFindByIds(): void
    {
        $this->sut->save($this->productOne);
        $this->sut->save($this->productTwo);
        $result = $this->sut->findByIds(['1', '2']);
        $this->assertCount(2, $result);
        $this->assertContains($this->productOne, $result);
        $this->assertContains($this->productTwo, $result);
    }

    public function testSave(): void
    {
        $this->sut->save($this->productOne);
        $this->sut->save($this->productTwo);
        $this->assertEquals($this->productOne, $this->sut->find('1'));
        $this->assertEquals($this->productTwo, $this->sut->find('2'));
    }
}
