<?php

declare(strict_types=1);

namespace Careship\Functional\Either;

use Jose\Component\Signature\Algorithm\None;

/**
 * @psalm-param list<string>|string $reasonStringOrList
 */
function convert_to_reason($reasonStringOrList): Reason
{
    return \is_string($reasonStringOrList) ?
        new SingleReason($reasonStringOrList) :
        new MultipleReasons(...array_map(
                function($reasonStringItem) {
                    return convert_to_reason($reasonStringItem);
                }, $reasonStringOrList)
        );
}

/**
 * @psalm-param list<string>|string $reasonStringOrList
 */
function no($reasonStringOrList): No
{
    $reason = convert_to_reason($reasonStringOrList);

    return new class($reason) implements No {
        /** @var Reason */
        private $reason;

        public function __construct(Reason $reason)
        {
            $this->reason = $reason;
        }

        public function yes(callable $f): Either
        {
            return $this;
        }

        public function no(callable $f): Either
        {
            return $f($this->reason) ?? $this;
        }

        public function extract()
        {
            return $this->reason;
        }
    };
}

/**
 * @template T
 * @psalm-param T|null $value
 */
function yes($value = null): Yes
{
    return new class($value) implements Yes {
        /** @psalm-var T|null */
        private $value;

        /**
         * @psalm-param T|null $value
         */
        public function __construct($value)
        {
            $this->value = $value;
        }

        public function yes(callable $f): Either
        {
            return $f($this->value) ?? $this;
        }

        public function no(callable $f): Either
        {
            return $this;
        }

        public function extract()
        {
            return $this->value;
        }
    };
}

/**
 * @template T
 * @template U
 * @template W
 * @psalm-param Either<T> $either
 * @psalm-param callable(T):U $yesHandler
 * @psalm-param callable(Reason):W $noHandler
 * @return U|W
 */
function handle_either(Either $either, callable $yesHandler, callable $noHandler) {
    switch (true) {
        case $either instanceof Yes:
            return $yesHandler($either->extract());
        case $either instanceof No:
            return $noHandler($either->extract());
        default:
            throw new \LogicException(sprintf('Unknown either type %s', get_class($either)));
    }
}