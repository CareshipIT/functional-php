<?php

declare(strict_types=1);

namespace Careship\Functional\Either;

/**
 * @template T
 * @extends Either<T>
 */
interface No extends Either
{
    /** @psalm-return Reason */
    public function extract();
}
