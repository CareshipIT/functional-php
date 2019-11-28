<?php

namespace Careship\Functional\Result;

/**
 * @template T
 * @psalm-param callable(mixed...):T $f
 * @psalm-param mixed[] $args
 *
 * @psalm-return Result<T>
 */
function result(callable $f, ...$args): Result
{
    try {
        $value = $f(...$args);

        return new Success($value);
    } catch (\Throwable $e) {
        return new Failure(new ExceptionStack($e));
    }
}

/**
 * @template T
 * @template U
 * @template W
 * @psalm-param Result<T> $result
 * @psalm-param callable(T):U $successHandler
 * @psalm-param callable(ExceptionStack):W $errorHandler
 * @psalm-return U|W
 */
function handle_result(Result $result, callable $successHandler, callable $errorHandler) {
    switch (true) {
        case $result instanceof Success:
            return $successHandler($result->extract());
        case $result instanceof Failure:
            return $errorHandler($result->extract());
        default:
            throw new \LogicException(sprintf('Unknown result type %s', get_class($result)));
    }
}

/**
 * @template T
 * @template U
 * @psalm-param Result<T> $result
 * @psalm-param callable(ExceptionStack):U $errorHandler
 * @psalm-return T|U
 */
function extract_or_handle_failure(Result $result, callable $errorHandler) {
    return handle_result(
        $result,
        /**
         * @psalm-param T $value
         * @psalm-return T
         */
        function ($value) { return $value; },
        $errorHandler
    );
}
