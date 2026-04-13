<?php

declare(strict_types=1);

namespace Thesis\Formatter;

/**
 * @api
 * @return non-empty-string
 */
function formatReflectedParameter(\ReflectionParameter $parameter): string
{
    $formattedFunction = formatReflectedFunction($parameter->getDeclaringFunction());

    return \sprintf('%s$%s)', substr($formattedFunction, 0, -1), $parameter->getName());
}
