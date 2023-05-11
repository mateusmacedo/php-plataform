<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Stubs;

enum MessageEnumStub: string
{
    case COMMAND = CommandStub::class;
    case QUERY = QueryStub::class;
}
