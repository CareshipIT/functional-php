<?php

namespace Careship\Functional\Result;

/**
 * @template T2
 * @implements Result<T2>
 */
final class Success implements Result
{
    /** @psalm-var T2 */
    private $value;

    /** @psalm-param T2 $value */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /** @psalm-return T2 */
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
