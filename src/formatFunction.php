<?php

declare(strict_types=1);

namespace Thesis\Formatter;

/**
 * @api
 * @param callable $function no native type to avoid autoloading on callable type check
 * @return non-empty-string
 * @phpstan-ignore missingType.callable
 */
function formatFunction(mixed $function): string
{
    if (\is_string($function)) {
        if (!str_contains($function, '::')) {
            return $function . '()';
        }

        $function = explode('::', $function);
    }

    if (\is_array($function)) {
        /** @var array{class-string|object, non-empty-string} $function */
        return \sprintf('%s::%s()', formatClass($function[0]), $function[1]);
    }

    if ($function instanceof \Closure) {
        return formatReflectedFunction(new \ReflectionFunction($function));
    }

    /** @var object $function */
    return \sprintf('%s()', formatClass($function)); // @phpstan-ignore varTag.type
}
