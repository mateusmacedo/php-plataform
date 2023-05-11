<?php

declare(strict_types=1);

namespace Core\Infrastructure;

use Core\Domain\IdGeneratorInterface;
use Ramsey\Uuid\Uuid;

class UuidGenerator implements IdGeneratorInterface
{
    public static function generate(): string
    {
        return Uuid::uuid4()->toString();
    }
}
