<?php

declare(strict_types=1);

namespace Thesis\Formatter;

/**
 * @api
 * @param callable $function no native type to avoid autoloading on callable type check
 * @param non-negative-int|non-empty-string $parameter
 * @return non-empty-string
 * @phpstan-ignore missingType.callable
 */
function formatParameter(mixed $function, int|string $parameter): string
{
    return \sprintf('%s$%s)', substr(formatFunction($function), 0, -1), $parameter);
}
