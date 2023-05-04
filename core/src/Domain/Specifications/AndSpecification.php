<?php

declare(strict_types=1);

namespace Core\Domain\Specifications;

class AndSpecification extends AbstractSpecification
{
    private SpecificationInterface $leftSpecification;
    private SpecificationInterface $rightSpecification;

    public function __construct(SpecificationInterface $leftSpecification, SpecificationInterface $rightSpecification)
    {
        $this->leftSpecification = $leftSpecification;
        $this->rightSpecification = $rightSpecification;
    }

    public function isSatisfiedBy(mixed $target): bool
    {
        return $this->leftSpecification->isSatisfiedBy($target) && $this->rightSpecification->isSatisfiedBy($target);
    }
}
