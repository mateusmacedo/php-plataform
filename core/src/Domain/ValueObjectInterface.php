<?php

declare(strict_types=1);

namespace Core\Domain;

interface ValueObjectInterface
{
    public function equals(ValueObjectInterface $valueObject): bool;
}
