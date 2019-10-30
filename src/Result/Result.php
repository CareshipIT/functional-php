<?php

namespace Careship\Functional\Result;

/** @template-covariant T */
interface Result
{
    /** @psalm-return T|ExceptionStack */
    public function extract();

    public function ok(callable $f): self;

    public function fail(callable $f): self;
}
