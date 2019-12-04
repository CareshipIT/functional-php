<?php

namespace Careship\Functional;

use Careship\Functional\Maybe\Some;
use Careship\Functional\Maybe\Maybe;
use Careship\Functional\Result\ExceptionStack;
use Careship\Functional\Result\Result;
use function Careship\Functional\Result\extract_or_handle_failure;

const FAIL_MESSAGE_FORMAT = "What failed: %s\nCaused by: %s";

/**
 * @template T
 * @psalm-param Result<Maybe<T>> $result
 * @psalm-return T
 */
function extract_some_or_fail(Result $result, string $failMessage)
{
    $maybe = extract_or_fail($result, $failMessage);

    return some_or_fail($maybe, $failMessage);
}

/**
 * @template T
 * @psalm-param Maybe<T> $maybe
 * @psalm-return T
 */
function some_or_fail(Maybe $maybe, string $failMessage)
{
    if (!$maybe instanceof Some) {
        $exceptionMessage = \sprintf(
            FAIL_MESSAGE_FORMAT,
            $failMessage,
            'Not found'
        );

        throw new \Exception($exceptionMessage);
    }

    return $maybe->extract();
}

/**
 * @template T
 * @psalm-param Result<T> $result
 * @psalm-return T
 */
function extract_or_fail(Result $result, string $failMessage)
{
    return extract_or_handle_failure(
        $result,
        function (ExceptionStack $exceptionStack) use ($failMessage) {
            $exceptionMessage = \sprintf(
                FAIL_MESSAGE_FORMAT,
                $failMessage,
                $exceptionStack->toString()
            );

            throw new \Exception($exceptionMessage);
        }
    );
}

/**
 * @template T
 * @psalm-param Result<T> $result
 */
function success_or_fail(Result $result, string $failMessage)
{
    extract_or_fail($result, $failMessage);
}
