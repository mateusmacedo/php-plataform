<?php

declare(strict_types=1);

namespace Core\Utils;

use DeepCopy\DeepCopy;

trait DeepCloneTrait
{
    public function clone()
    {
        return (new DeepCopy())->copy($this);
    }
}
