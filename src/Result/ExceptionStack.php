<?php

namespace Careship\Functional\Result;

final class ExceptionStack
{
    /** @var \Throwable[] * */
    private $stack;

    public function __construct(\Throwable ...$exceptions)
    {
        $this->stack = $exceptions;
    }

    public function merge(self $exceptionStack)
    {
        return new self(...\array_merge($this->stack, $exceptionStack->stack));
    }

    public function getLastException(): \Throwable
    {
        /** @var \Throwable $lastException */
        $lastException = \end($this->stack);

        return $lastException;
    }

    public function toString(): string
    {
        $string = '';

        foreach ($this->stack as $exception) {
            $string .= $exception->getMessage() . PHP_EOL;
            $string .= $exception->getTraceAsString() . PHP_EOL;
        }

        return $string;
    }
}
