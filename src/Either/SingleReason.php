<?php

namespace Careship\Functional\Either;

final class SingleReason implements Reason
{
    /** @var string */
    private $reason;

    public function __construct(string $reason)
    {
        $this->reason = $reason;
    }

    public function toString(): string
    {
        return $this->reason;
    }
}
