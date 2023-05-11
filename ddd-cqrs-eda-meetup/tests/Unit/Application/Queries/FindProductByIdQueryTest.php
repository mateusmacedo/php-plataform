<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Queries;

use App\Application\Queries\FindProductByIdQuery;
use Tests\BaseTestCase;

final class FindProductByIdQueryTest extends BaseTestCase
{
    public function testConstructorSetsProperties()
    {
        $productId = '123';
        $query = new FindProductByIdQuery($productId);

        $this->assertSame($productId, $query->productId);
    }
}
