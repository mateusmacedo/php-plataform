<?php

declare(strict_types=1);

namespace Core\Application;

use Core\Domain\DomainException;

/**
 * Class ApplicationException is the base exception for the application layer
 *
 * @package Core\Application
 * @author Mateus Macedo Dos Anjos <macedodosanjosmateus@gmail.com>
 * @version 0.0.1
 */
class ApplicationException extends DomainException
{
}
