<?php

namespace Careship\Tests\Functional\Result;

use Careship\Functional\Result\Aborted;
use Careship\Functional\Result\ExceptionStack;
use Careship\Functional\Result\Failure;
use Careship\Functional\Result\Success;
use PHPUnit\Framework\TestCase;

final class FailureTest extends TestCase
{
    /** @test */
    public function extract_returns_exception_stack()
    {
        $failure = new Failure(new ExceptionStack());

        $this->assertInstanceOf(ExceptionStack::class, $failure->extract());
    }

    /** @test */
    public function fail_executes_a_new_result()
    {
        $exceptionStack = new ExceptionStack();
        $failure = new Failure($exceptionStack);

        $spy = $this->getMockBuilder(\stdClass::class)->addMethods(['myMethod'])->getMock();
        $spy->expects($this->once())->method('myMethod');

        $failure->fail(function(ExceptionStack $exceptionStack) use ($spy) {
            $spy->myMethod();
        });
    }

    /** @test */
    public function fail_returns_aborted()
    {
        $failure = new Failure(new ExceptionStack());

        $newResult = $failure->fail(function() {});

        $this->assertInstanceOf(Aborted::class, $newResult);
    }

    /** @test */
    public function ok_is_a_no_op()
    {
        $failure = new Failure(new ExceptionStack());

        $spy = $this->getMockBuilder(\stdClass::class)->addMethods(['myMethod'])->getMock();
        $spy->expects($this->never())->method('myMethod')->with();

        $newResult = $failure->ok(function() use ($spy) {
            $spy->myMethod();
        });

        $this->assertInstanceOf(Failure::class, $newResult);
    }

    /** @test */
    public function double_failure_stacks_error_in_the_exception_stack()
    {
        $exceptionStack = new ExceptionStack(
            new \Exception('first exception')
        );
        $failure = new Failure($exceptionStack);

        $newResult = $failure->fail(function(ExceptionStack $exceptionStack) {
            throw new \Exception('second exception');
        });

        $expectedExceptionStack = new ExceptionStack(
            new \Exception('first exception'),
            new \Exception('second exception')
        );

        $this->assertEquals($expectedExceptionStack, $newResult->extract());
    }
}
