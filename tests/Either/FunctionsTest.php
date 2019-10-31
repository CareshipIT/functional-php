<?php

namespace Careship\Tests\Functional\Either;

use Careship\Functional\Either\MultipleReasons;
use Careship\Functional\Either\No;
use Careship\Functional\Either\Reason;
use Careship\Functional\Either\SingleReason;
use Careship\Functional\Either\Yes;
use PHPUnit\Framework\TestCase;
use function Careship\Functional\Either\convert_to_reason;
use function Careship\Functional\Either\no;
use function Careship\Functional\Either\yes;

final class FunctionsTest extends TestCase
{
    /** @test */
    public function no_returns_a_No()
    {
        $no = no('reason');

        $this->assertInstanceOf(No::class, $no);
    }

    /** @test */
    public function yes_returns_a_Yes()
    {
        $yes = yes('value');

        $this->assertInstanceOf(Yes::class, $yes);
    }

    /** @test */
    public function yes_extract_returns_value()
    {
        $yes = yes('value');

        $this->assertEquals('value', $yes->extract());
    }

    /** @test */
    public function no_extract_returns_single_reason()
    {
        $no = no('reason');

        $this->assertInstanceOf(SingleReason::class, $no->extract());
    }

    /** @test */
    public function no_extract_returns_multiple_reason()
    {
        $no = no(['reason 1', 'reason 2']);

        $this->assertInstanceOf(MultipleReasons::class, $no->extract());
    }

    /** @test */
    public function Yes_yes_function_is_executed()
    {
        $yes = yes('value');

        $spy = $this->getMockBuilder(\stdClass::class)->addMethods(['myMethod'])->getMock();
        $spy->expects($this->once())->method('myMethod');

        $yes->yes(function() use ($spy) {
            $spy->myMethod();
        });
    }

    /** @test */
    public function No_yes_function_is_a_no_op()
    {
        $no = no('reason');

        $spy = $this->getMockBuilder(\stdClass::class)->addMethods(['myMethod'])->getMock();
        $spy->expects($this->never())->method('myMethod');

        $no->yes(function() use ($spy) {
            $spy->myMethod();
        });
    }

    /** @test */
    public function No_no_function_is_executed()
    {
        $no = no('reason');

        $spy = $this->getMockBuilder(\stdClass::class)->addMethods(['myMethod'])->getMock();
        $spy->expects($this->once())->method('myMethod');

        $no->no(function() use ($spy) {
            $spy->myMethod();
        });
    }

    /** @test */
    public function Yes_no_function_is_a_no_op()
    {
        $yes = yes('value');

        $spy = $this->getMockBuilder(\stdClass::class)->addMethods(['myMethod'])->getMock();
        $spy->expects($this->never())->method('myMethod');

        $yes->no(function() use ($spy) {
            $spy->myMethod();
        });
    }

    /** @test */
    public function convert_to_reason_returns_nested_multiple_reasons()
    {
        $reason = convert_to_reason(['first', ['second', 'third'], 'fourth', [['fifth']]]);
        $expectedReason = new MultipleReasons(
            new SingleReason('first'),
            new MultipleReasons(
                new SingleReason('second'),
                new SingleReason('third'),
            ),
            new SingleReason('fourth'),
            new MultipleReasons(
                new MultipleReasons(
                    new SingleReason('fifth')
                )
            )
        );

        $this->assertEquals($expectedReason, $reason);
    }
}
