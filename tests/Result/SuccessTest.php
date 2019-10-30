<?php

namespace Careship\Tests\Functional\Result;

use Careship\Functional\Result\Success;
use PHPUnit\Framework\TestCase;

final class SuccessTest extends TestCase
{
    /** @test */
    public function extract_returns_value()
    {
        $success = new Success('foo');

        $this->assertEquals('foo', $success->extract());
    }

    /** @test */
    public function ok_executes_a_new_result()
    {
        $success = new Success('foo');

        $spy = $this->getMockBuilder(\stdClass::class)->addMethods(['myMethod'])->getMock();
        $spy->expects($this->once())->method('myMethod');

        $newResult = $success->ok(function() use ($spy) {
            $spy->myMethod();
        });

        $this->assertInstanceOf(Success::class, $newResult);
    }

    /** @test */
    public function fail_is_a_no_op()
    {
        $success = new Success('foo');

        $spy = $this->getMockBuilder(\stdClass::class)->addMethods(['myMethod'])->getMock();
        $spy->expects($this->never())->method('myMethod');

        $newResult = $success->fail(function() use ($spy) {
            $spy->myMethod();
        });

        $this->assertInstanceOf(Success::class, $newResult);
    }
}
