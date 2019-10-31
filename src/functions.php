<?php

namespace Careship\Functional;

use Careship\Functional\Maybe\Some;
use Careship\Functional\Maybe\Maybe;
use Careship\Functional\Result\Failure;
use Careship\Functional\Result\Result;

const FAIL_MESSAGE_FORMAT = "What failed: %s\nCaused by: %s";

/**
 * @template T
 * @psalm-param Result<Maybe<T>> $result
 * @psalm-return T
 */
function get_some_or_fail(Result $result, string $failMessage)
{
    $maybe = success_or_fail($result, $failMessage);

    return extract_some_or_fail($maybe, $failMessage);
}

/**
 * @template T
 * @psalm-param Maybe<T> $maybe
 * @psalm-return T
 */
function extract_some_or_fail(Maybe $maybe, string $failMessage)
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
function success_or_fail(Result $result, string $failMessage)
{
    if ($result instanceof Failure) {
        $exceptionStack = $result->extract();
        $exceptionMessage = \sprintf(
            FAIL_MESSAGE_FORMAT,
            $failMessage,
            $exceptionStack->toString()
        );

        throw new \Exception($exceptionMessage);
    }

    /** @psalm-var T $value */
    $value = $result->extract();

    return $value;
}
