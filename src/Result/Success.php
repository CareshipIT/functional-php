<?php

namespace Careship\Functional\Result;

/**
 * @template T
 * @implements Result<T>
 */
final class Success implements Result
{
    /** @psalm-var T */
    private $value;

    /** @psalm-param T $value */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /** @psalm-return T */
    public function extract()
    {
        return $this->value;
    }

    public function ok(callable $f): Result
    {
        return result($f, $this->value);
    }

    public function fail(callable $f): Result
    {
        return $this;
    }
}
