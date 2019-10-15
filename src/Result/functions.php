<?php

namespace Careship\Functional\Result;

/**
 * @template T
 * @psalm-param callable(mixed...):T $f
 *
 * @return Result<T>
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
