<?php

namespace Careship\Tests\Functional\Result;

use Careship\Functional\Result\ExceptionStack;
use PHPUnit\Framework\TestCase;

final class ExceptionStackTest extends TestCase
{
    /** @test */
    public function merge_returns_a_new_instance_with_exceptions_from_both_stacks()
    {
        $exceptionStack1 = new ExceptionStack(new \Exception('exception 1'));
        $exceptionStack2 = new ExceptionStack(
            new \Exception('exception 2'),
            new \Exception('exception 3')
        );

        $expectedExceptionStack = new ExceptionStack(
            new \Exception('exception 1'),
            new \Exception('exception 2'),
            new \Exception('exception 3'),
        );

        $this->assertEquals($expectedExceptionStack, $exceptionStack1->merge($exceptionStack2));
    }

    /** @test */
    public function last_exception_in_the_stack_is_returned()
    {
        $exceptionStack = new ExceptionStack(
            new \Exception('exception 1'),
            new \Exception('exception 2'),
            new \Exception('exception 3')
        );

        $expectedException = new \Exception('exception 3');

        $this->assertEquals($expectedException, $exceptionStack->getLastException());
    }

    /** @test */
    public function to_string()
    {
        $exceptionStack = new ExceptionStack(
            new \Exception('exception 1'),
            new \Exception('exception 2')
        );

        $exceptionStackString = $exceptionStack->toString();

        $this->assertStringContainsString('exception 1', $exceptionStackString);
        $this->assertStringContainsString('exception 2', $exceptionStackString);
    }
}
