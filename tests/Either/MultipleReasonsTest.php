<?php

namespace Careship\Tests\Functional\Either;

use Careship\Functional\Either\MultipleReasons;
use Careship\Functional\Either\No;
use Careship\Functional\Either\Reason;
use Careship\Functional\Either\SingleReason;
use Careship\Functional\Either\Yes;
use PhpParser\Node\Expr\AssignOp\Mul;
use PHPUnit\Framework\TestCase;
use function Careship\Functional\Either\convert_to_reason;
use function Careship\Functional\Either\no;
use function Careship\Functional\Either\yes;

final class MultipleReasonsTest extends TestCase
{
    /** @test */
    public function complicated_to_string()
    {
        $multipleReasons = new MultipleReasons(
            new SingleReason('one'),
            new MultipleReasons(
                new SingleReason('two'),
                new SingleReason('three'),
            ),
            new SingleReason('four'),
            new MultipleReasons(
                new SingleReason('five'),
                new MultipleReasons(new SingleReason('six')),
                new SingleReason('seven'),
            ),
        );

        $expectedToString = <<<STR
- one
- - two
- - three
- four
- - five
- - - six
- - seven
STR;

        $this->assertEquals($expectedToString, $multipleReasons->toString());
    }
}
