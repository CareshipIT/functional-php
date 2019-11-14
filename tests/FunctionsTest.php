<?php

namespace Careship\Tests\Functional;

use Careship\Functional\Result\ExceptionStack;
use Careship\Functional\Result\Failure;
use Careship\Functional\Result\Success;
use PHPUnit\Framework\TestCase;
use function Careship\Functional\extract_some_or_fail;
use function Careship\Functional\Maybe\none;
use function Careship\Functional\Maybe\some;
use function Careship\Functional\extract_or_fail;

final class FunctionsTest extends TestCase
{
    /** @test */
    public function extract_or_fail_success()
    {
        $value = extract_or_fail(new Success('value'), 'Fail message');

        $this->assertSame('value', $value);
    }

    /** @test */
    public function extract_or_fail_failure_error_message()
    {
        $this->expectExceptionMessageMatches("/^What failed: Fail message\nCaused by: Exception message/");

        $exceptionStack = new ExceptionStack(
            new \Exception('Exception message')
        );

        extract_or_fail(new Failure($exceptionStack), 'Fail message');
    }

    /** @test */
    public function extract_some_or_fail_success()
    {
        $value = extract_some_or_fail(new Success(some('value')), 'Fail message');

        $this->assertSame('value', $value);
    }

    /** @test */
    public function extract_some_or_fail_not_found_message()
    {
        $this->expectExceptionMessageMatches("/^What failed: Fail message\nCaused by: Not found/");

        extract_some_or_fail(new Success(none()), 'Fail message');
    }
}
