<?php

namespace Careship\Functional\Either;

interface Either
{
    public function extract();

    public function yes(callable $f): self;

    public function no(callable $f): self;
}
