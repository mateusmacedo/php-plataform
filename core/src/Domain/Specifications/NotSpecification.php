<?php

declare(strict_types=1);

namespace Core\Domain\Specifications;

class NotSpecification extends AbstractSpecification
{
    private SpecificationInterface $specification;

    public function __construct(SpecificationInterface $specification)
    {
        $this->specification = $specification;
    }

    public function isSatisfiedBy(mixed $target): bool
    {
        return !$this->specification->isSatisfiedBy($target);
    }
}
