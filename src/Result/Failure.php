<?php

namespace Careship\Functional\Result;

/**
 * @template T2
 * @implements Result<T2>
 */
class Failure implements Result
{
    private $exceptionStack;

    public function __construct(ExceptionStack $exceptionStack)
    {
        $this->exceptionStack = $exceptionStack;
    }

    public function extract(): ExceptionStack
    {
        return $this->exceptionStack;
    }

    public function ok(callable $f): Result
    {
        return $this;
    }

    public function fail(callable $f): Result
    {
        $exceptionStack = $this->exceptionStack;

        try {
            $f($this->exceptionStack);
        } catch (\Throwable $e) {
            $exceptionStack = $exceptionStack->merge(new ExceptionStack($e));
        }

        return new Aborted($exceptionStack);
    }
}
