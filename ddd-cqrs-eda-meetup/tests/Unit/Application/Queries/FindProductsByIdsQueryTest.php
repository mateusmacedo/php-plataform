<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Queries;

use App\Application\Queries\FindProductsByIdsQuery;
use Tests\BaseTestCase;

final class FindProductsByIdsQueryTest extends BaseTestCase
{
    public function testConstructorSetsProperties()
    {
        $productIds = ['123', '456'];
        $query = new FindProductsByIdsQuery($productIds);

        $this->assertSame($productIds, $query->productIds);
    }
}
