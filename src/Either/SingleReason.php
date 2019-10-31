<?php

namespace Careship\Functional\Either;

final class SingleReason implements Reason
{
    /** @var string */
    private $reasonString;

    public function __construct(string $reasonString)
    {
        $this->reasonString = $reasonString;
    }

    public function toString(?string $prefix = null, int $level = 0): string
    {
        $prefix = $level === 0 ?
            strval($prefix) :
            str_repeat(strval($prefix), $level);

        return $prefix . $this->reasonString;
    }
}
