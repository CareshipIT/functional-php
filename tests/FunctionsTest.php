<?php

namespace Careship\Tests\Functional;

use Careship\Functional\Result\ExceptionStack;
use Careship\Functional\Result\Failure;
use Careship\Functional\Result\Success;
use PHPUnit\Framework\TestCase;
use function Careship\Functional\get_some_or_fail;
use function Careship\Functional\Maybe\none;
use function Careship\Functional\Maybe\some;
use function Careship\Functional\success_or_fail;

/**
 * @group unit
 */
final class FunctionsTest extends TestCase
{
    /** @test */
    public function success_or_fail_success()
    {
        $value = success_or_fail(new Success('value'), 'Fail message');

        $this->assertSame('value', $value);
    }

    /** @test */
    public function success_or_fail_success_failure_error_message()
    {
        $this->expectExceptionMessageRegExp("/^What failed: Fail message\nCaused by: Exception message/");

        $exceptionStack = new ExceptionStack(
            new \Exception('Exception message')
        );

        success_or_fail(new Failure($exceptionStack), 'Fail message');
    }

    /** @test */
    public function get_some_or_fail_success()
    {
        $value = get_some_or_fail(new Success(some('value')), 'Fail message');

        $this->assertSame('value', $value);
    }

    /** @test */
    public function get_some_or_fail_not_found_message()
    {
        $this->expectExceptionMessageRegExp("/^What failed: Fail message\nCaused by: Not found/");

        get_some_or_fail(new Success(none()), 'Fail message');
    }
}
