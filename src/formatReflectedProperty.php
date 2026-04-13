<?php

declare(strict_types=1);

namespace Thesis\Formatter;

/**
 * @api
 * @return non-empty-string
 */
function formatReflectedProperty(\ReflectionProperty $property): string
{
    return formatProperty($property->class, $property->name);
}
