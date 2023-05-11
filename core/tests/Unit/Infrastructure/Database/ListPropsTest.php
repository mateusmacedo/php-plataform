<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Database;

use Core\Infrastructure\Database\ListProps;
use Tests\BaseTestCase;

final class ListPropsTest extends BaseTestCase
{
    public function testCreateListProps(): void
    {
        $listProps = ListProps::create(1, 10, ['name' => 'John Doe', 'age' => 30]);

        $this->assertInstanceOf(ListProps::class, $listProps);
        $this->assertEquals(1, $listProps->page);
        $this->assertEquals(10, $listProps->perPage);
        $this->assertEquals(['name' => 'John Doe', 'age' => 30], $listProps->filters);
    }

    public function testCreateListPropsWithoutPageAndPerPage(): void
    {
        $listProps = ListProps::create(null, null, ['name' => 'John Doe']);

        $this->assertInstanceOf(ListProps::class, $listProps);
        $this->assertNull($listProps->page);
        $this->assertNull($listProps->perPage);
        $this->assertEquals(['name' => 'John Doe'], $listProps->filters);
    }

    public function testCreateEmptyListProps(): void
    {
        $listProps = ListProps::create();

        $this->assertInstanceOf(ListProps::class, $listProps);
        $this->assertNull($listProps->page);
        $this->assertNull($listProps->perPage);
        $this->assertEquals([], $listProps->filters);
    }
}
