<?php

namespace Careship\Tests\Functional\Result;

use Careship\Functional\Result\Aborted;
use Careship\Functional\Result\ExceptionStack;
use PHPUnit\Framework\TestCase;

final class AbortedTest extends TestCase
{
    /** @test */
    public function ok_is_a_no_op()
    {
        $aborted = new Aborted(new ExceptionStack());

        $spy = $this->getMockBuilder(\stdClass::class)->addMethods(['myMethod'])->getMock();
        $spy->expects($this->never())->method('myMethod');

        $newResult = $aborted->ok(function() use ($spy) {
            $spy->myMethod();
        });

        $this->assertInstanceOf(Aborted::class, $newResult);
    }

    /** @test */
    public function fail_is_a_no_op()
    {
        $aborted = new Aborted(new ExceptionStack());

        $spy = $this->getMockBuilder(\stdClass::class)->addMethods(['myMethod'])->getMock();
        $spy->expects($this->never())->method('myMethod');

        $newResult = $aborted->fail(function() use ($spy) {
            $spy->myMethod();
        });

        $this->assertInstanceOf(Aborted::class, $newResult);
    }
}
