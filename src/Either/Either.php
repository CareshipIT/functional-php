<?php

namespace Careship\Functional\Either;

/** @template-covariant T */
interface Either
{
    public function yes(callable $f): self;

    public function no(callable $f): self;
}
