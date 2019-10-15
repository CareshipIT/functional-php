<?php

namespace Careship\Functional\Either;

final class MultipleReasons implements Reason
{
    /** @var Reason[] */
    private $reasons;

    public function __construct(Reason ...$reasons)
    {
        $this->reasons = $reasons;
    }

    public function getReasons(): array
    {
        return $this->reasons;
    }

    public function toString(): string
    {
        return \array_reduce(
            $this->reasons,
            function ($reasonsString, Reason $reason) {
                $reasonsString .= $reason->toString() . PHP_EOL;

                return $reasonsString;
            }, '');
    }
}
