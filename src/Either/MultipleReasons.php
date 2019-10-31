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

    public function toString(?string $prefix = null, int $level = 0): string
    {
        $prefix = $prefix === null ? '- ' : $prefix;

        $reasonStrings = \array_map(
            function (Reason $reason) use ($prefix, $level) {
                return $reason->toString($prefix, $level + 1);
            },
            $this->reasons
        );

        return implode(PHP_EOL, $reasonStrings);
    }
}
