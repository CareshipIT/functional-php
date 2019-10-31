<?php

namespace Careship\Functional\Either;

interface Reason
{
    public function toString(?string $prefix = null, int $level = 0): string;
}
