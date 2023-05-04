<?php

declare(strict_types=1);

namespace Core\Domain;

interface IdGeneratorInterface
{
    public static function generate(): string|int;
}
