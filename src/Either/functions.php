<?php

declare(strict_types=1);

namespace Careship\Functional\Either;

/**
 * @param array|string $reason
 *
 * @return Either
 */
function no($reason): Either
{
    $reason = \is_string($reason) ?
        new SingleReason($reason) :
        new MultipleReasons(...$reason);

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
function yes($value = null): Either
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
