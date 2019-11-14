<?php

namespace Careship\Functional\Either;

/**
 * @template T
 * @extends Either<T>
 */
interface Yes extends Either
{
    /** @psalm-return T */
    public function extract();
}
