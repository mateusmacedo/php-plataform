<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Ecotone\Converters\Stubs;

class MessageStub
{
    public function __construct(public readonly ?string $name='unnamed', public readonly ?int $age = 0, private readonly ?string $private = 'private')
    {
    }
}
