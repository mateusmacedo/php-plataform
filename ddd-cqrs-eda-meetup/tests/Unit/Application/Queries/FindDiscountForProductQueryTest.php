<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Queries;

use App\Application\Queries\FindDiscountForProductQuery;
use Tests\BaseTestCase;

final class FindDiscountForProductQueryTest extends BaseTestCase
{
    public function testConstructorSetsProperties()
    {
        $productId = '123';
        $discountCode = 'test';
        $query = new FindDiscountForProductQuery($productId, $discountCode);

        $this->assertSame($productId, $query->productId);
        $this->assertSame($discountCode, $query->discountCode);
    }
}
