<?php

declare(strict_types=1);

namespace Thesis\Formatter;

/**
 * @api
 * @param \ReflectionClass<*> $class
 * @return non-empty-string
 */
function formatReflectedClass(\ReflectionClass $class): string
{
    return formatClass($class->name);
}
