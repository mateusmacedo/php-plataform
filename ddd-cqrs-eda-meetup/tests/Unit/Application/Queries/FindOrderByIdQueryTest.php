<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Queries;

use App\Application\Queries\FindOrderByIdQuery;
use Tests\BaseTestCase;

final class FindOrderByIdQueryTest extends BaseTestCase
{
    public function testConstructorSetsProperties()
    {
        $orderId = '123';
        $query = new FindOrderByIdQuery($orderId);

        $this->assertSame($orderId, $query->orderId);
    }
}
