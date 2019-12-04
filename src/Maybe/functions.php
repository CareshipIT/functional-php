<?php

declare(strict_types=1);

namespace Careship\Functional\Maybe;

function none(): None
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
function some($value): Some
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

/**
 * @template T
 * @template U
 * @template W
 * @psalm-param Maybe<T> $maybe
 * @psalm-param callable(T):U $someHandler
 * @psalm-param callable():W $noneHandler
 * @psalm-return U|W
 */
function handle_maybe(Maybe $maybe, callable $someHandler, callable $noneHandler) {
    switch (true) {
        case $maybe instanceof Some:
            return $someHandler($maybe->extract());
        case $maybe instanceof None:
            return $noneHandler();
        default:
            throw new \LogicException(sprintf('Unknown maybe type %s', get_class($maybe)));
    }
}
