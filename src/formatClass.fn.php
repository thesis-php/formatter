<?php

declare(strict_types=1);

namespace Typhoon\Formatter;

/**
 * @api
 * @param class-string|object $class
 * @return non-empty-string
 */
function formatClass(string|object $class): string
{
    if (\is_object($class)) {
        $class = $class::class;
    }

    if (preg_match('/^(.+)anonymous\x00(.+?)[:(](\d+)/', $class, $matches) === 1) {
        return "{$matches[1]}{$matches[2]}:{$matches[3]}";
    }

    return $class;
}
