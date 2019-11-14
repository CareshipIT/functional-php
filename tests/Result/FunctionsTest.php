<?php

namespace Careship\Tests\Functional\Result;

use Careship\Functional\Result\Failure;
use Careship\Functional\Result\Success;
use PHPUnit\Framework\TestCase;
use function Careship\Functional\Result\result;

final class FunctionsTest extends TestCase
{
    /** @test */
    public function result_returns_success()
    {
        $result = result(function() { return true; });

        $this->assertInstanceOf(Success::class, $result);
    }

    /** @test */
    public function result_returns_failure_on_exception()
    {
        $result = result(function() { throw new \Exception(); });

        $this->assertInstanceOf(Failure::class, $result);
    }
}
