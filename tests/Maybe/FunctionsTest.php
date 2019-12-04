<?php

namespace Careship\Tests\Functional\Maybe;

use Careship\Functional\Maybe\None;
use Careship\Functional\Maybe\Some;
use PHPUnit\Framework\MockObject\Rule\InvokedCount as InvokedCountMatcher;
use PHPUnit\Framework\TestCase;
use function Careship\Functional\Maybe\handle_maybe;
use function Careship\Functional\Maybe\none;
use function Careship\Functional\Maybe\some;

final class FunctionsTest extends TestCase
{
    /** @test */
    public function none_returns_a_None()
    {
        $none = none();

        $this->assertInstanceOf(None::class, $none);
    }

    /** @test */
    public function some_returns_a_Some()
    {
        $some = some('value');

        $this->assertInstanceOf(Some::class, $some);
    }

    /** @test */
    public function some_extract_returns_value()
    {
        $some = some('value');

        $this->assertEquals('value', $some->extract());
    }

    /** @test */
    public function some_if_some_is_executed()
    {
        $some = some('value');

        $spy = $this->getMockBuilder(\stdClass::class)->addMethods(['myMethod'])->getMock();
        $spy->expects($this->once())->method('myMethod');

        $some->ifSome(function() use ($spy) {
            $spy->myMethod();
        });
    }

    /** @test */
    public function none_if_some_is_a_no_op()
    {
        $none = none();

        $spy = $this->getMockBuilder(\stdClass::class)->addMethods(['myMethod'])->getMock();
        $spy->expects($this->never())->method('myMethod');

        $none->ifSome(function() use ($spy) {
            $spy->myMethod();
        });
    }

    /** @test */
    public function some_handler_is_invoked_in_handle_maybe()
    {
        $someSpy = $this->getMockBuilder(\stdClass::class)->addMethods(['spy'])->getMock();
        $someSpy->expects($this->exactly(1))->method('spy');

        $noneSpy = $this->getMockBuilder(\stdClass::class)->addMethods(['spy'])->getMock();
        $noneSpy->expects($this->never())->method('spy');

        handle_maybe(
            some('foo'),
            [$someSpy, 'spy'],
            [$noneSpy, 'spy']
        );
    }

    /** @test */
    public function none_handler_is_invoked_in_handle_maybe()
    {
        $someSpy = $this->getMockBuilder(\stdClass::class)->addMethods(['spy'])->getMock();
        $someSpy->expects($this->never())->method('spy');

        $noneSpy = $this->getMockBuilder(\stdClass::class)->addMethods(['spy'])->getMock();
        $noneSpy->expects($this->exactly(1))->method('spy');

        handle_maybe(
            none(),
            [$someSpy, 'spy'],
            [$noneSpy, 'spy']
        );
    }
}
