<?php

namespace Core\Domain\Validators;

interface ValidatorInterface
{
    public function isValid($value);

    public function getMessages();

    public function getKey(): string;
}
