<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Stubs;

use stdClass;

enum ActionsEnumStub: string
{
    case stubed = stdClass::class;
}
