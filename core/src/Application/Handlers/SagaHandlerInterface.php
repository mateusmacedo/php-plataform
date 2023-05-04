<?php

declare(strict_types=1);

namespace Core\Application\Handlers;

/**
 * Interface SagaInterface
 *
 * @package Core\Application
 * @author Mateus Macedo Dos Anjos <macedodosanjosmateus@gmail.com>
 * @since 0.0.1
 * @version 0.0.1
 */
interface SagaHandlerInterface extends HandlerInterface
{
    /**
     * Initialize the saga
     *
     * @return void
     */
    public function start(): void;

    /**
     * Compensate transaction
     *
     * @return void
     */
    public function compensate(): void;
}
