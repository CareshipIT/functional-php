<?php

declare(strict_types=1);

namespace Careship\Functional\Maybe;

/** @template T */
interface Maybe
{
    public function ifSome(callable $f): self;
}
