<?php

declare(strict_types=1);

namespace Careship\Functional\Maybe;

/**
 * @template T2
 * @extends Maybe<T2>
 */
interface Some extends Maybe
{
    /** @psalm-return T2 */
    public function extract();
}
