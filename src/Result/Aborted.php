<?php

namespace Careship\Functional\Result;

final class Aborted extends Failure
{
    public function ok(callable $f): Result
    {
        return $this;
    }

    public function fail(callable $f): Result
    {
        return $this;
    }
}
