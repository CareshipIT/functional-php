<?php

namespace Careship\Functional\Either;

/** @template-covariant T */
interface Either
{
    /** @psalm-return T|Reason */
    public function extract();

    public function yes(callable $f): self;

    public function no(callable $f): self;
}
