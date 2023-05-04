<?php

declare(strict_types=1);

namespace Core\Domain\Specifications;

interface SpecificationInterface
{
    public function isSatisfiedBy(mixed $target): bool;

    public function and(SpecificationInterface $specification): SpecificationInterface;

    public function or(SpecificationInterface $specification): SpecificationInterface;

    public function not(): SpecificationInterface;
}
