<?php

declare(strict_types=1);

namespace Core\Application\Handlers;

/**
 * Interface SagaInterface.
 *
 * @author Mateus Macedo Dos Anjos <macedodosanjosmateus@gmail.com>
 *
 * @since 0.0.1
 *
 * @version 0.0.1
 */
interface SagaHandlerInterface extends HandlerInterface
{
    /**
     * Initialize the saga.
     */
    public function start(): void;

    /**
     * Compensate transaction.
     */
    public function compensate(): void;
}
