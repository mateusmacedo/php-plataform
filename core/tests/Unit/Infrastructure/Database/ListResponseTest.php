<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Database;

use Core\Infrastructure\Database\ListResponse;
use Tests\BaseTestCase;

final class ListResponseTest extends BaseTestCase
{
    public function testListResponseCreation(): void
    {
        $data = [
            ['id' => 1, 'name' => 'John'],
            ['id' => 2, 'name' => 'Jane'],
        ];

        $listResponse = ListResponse::create($data, 10, 100);

        $this->assertInstanceOf(ListResponse::class, $listResponse);
        $this->assertIsArray($listResponse->rows);
        $this->assertEquals($data, $listResponse->rows);
        $this->assertEquals(10, $listResponse->perPage);
        $this->assertEquals(100, $listResponse->totalRows);
    }
}
