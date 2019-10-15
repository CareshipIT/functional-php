<?php

declare(strict_types=1);

namespace Careship\Functional\Maybe;

function none(): Maybe
{
    return new class() implements None {
        public function ifSome(callable $f): Maybe
        {
            return none();
        }
    };
}

/**
 * @template T
 * @psalm-param T $value
 * @psalm-return Some<T>
 */
function some($value): Maybe
{
    return new class($value) implements Some {
        /** @psalm-var T */
        private $value;

        /** @psalm-param T $value */
        public function __construct($value)
        {
            $this->value = $value;
        }

        public function extract()
        {
            return $this->value;
        }

        public function ifSome(callable $f): Maybe
        {
            return $f($this->extract()) ?? $this;
        }
    };
}
