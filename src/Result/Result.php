<?php

namespace Careship\Functional\Result;

/** @template-covariant T */
interface Result
{
    public function extract();

    public function ok(callable $f): self;

    public function fail(callable $f): self;
}
