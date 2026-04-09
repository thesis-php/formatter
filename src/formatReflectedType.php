<?php

declare(strict_types=1);

namespace Typhoon\Formatter;

/**
 * @api
 */
function formatReflectedType(?\ReflectionType $type): string
{
    if ($type === null) {
        return '';
    }

    if ($type instanceof \ReflectionNamedType) {
        $string = $type->getName();

        if ($type->allowsNull() && $string !== 'null' && $string !== 'mixed') {
            return '?' . $string;
        }

        return $string;
    }

    if ($type instanceof \ReflectionUnionType) {
        return implode('|', array_map(formatReflectedType(...), $type->getTypes()));
    }

    \assert($type instanceof \ReflectionIntersectionType);

    return implode('&', array_map(formatReflectedType(...), $type->getTypes()));
}
