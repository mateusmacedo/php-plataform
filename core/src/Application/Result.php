<?php

declare(strict_types=1);

namespace Core\Application;

use Exception;

/**
 * Class Result is a monad that represents the result of an operation
 *
 * @package Core\Application
 * @author Mateus Macedo Dos Anjos <macedodosanjosmateus@gmail.com>
 * @version 0.0.1
 */
class Result
{
    /**
     * Result constructor.
     *
     * @param bool $isSuccess
     * @param mixed $value
     * @param ApplicationException $error
     */
    private function __construct(public readonly bool $isSuccess, public readonly mixed $value = null, public readonly ?Exception $error)
    {
    }

    /**
     * Success constructor
     *
     * @param mixed $value
     * @return Result
     */
    public static function success($value): Result
    {
        return new self(true, $value, null);
    }

    /**
    * Failure constructor
    *
    * @param Exception $error
    * @return Result
    */
    public static function failure(Exception $error): Result
    {
        return new self(false, null, $error);
    }

    /**
     * Check if the result is a success
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->isSuccess;
    }

    /**
     * Map the result value as a parameter for the callable function
     * and return a new result
     *
     * @param callable $function
     * @return Result
     */
    public function mapTo(callable $function): Result
    {
        if ($this->isSuccess) {
            try {
                return self::success($function($this->value));
            } catch (Exception $e) {
                return self::failure(new ApplicationException($e->getMessage()));
            }
        }

        return $this;
    }
}
