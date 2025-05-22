<?php

declare(strict_types=1);

namespace Typhoon\Formatter;

/**
 * @api
 * @return non-empty-string
 */
function formatReflectedFunction(\ReflectionFunctionAbstract $function): string
{
    if (str_contains($function->getName(), '{closure')) {
        $file = $function->getFileName();

        if ($file === false) {
            return 'function()';
        }

        if (preg_match('/^(.*)\((\d+)\)/', $file, $matches)) {
            return \sprintf('function@%s:%d()', $matches[1], $matches[2]);
        }

        $line = $function->getStartLine();

        if ($line === false) {
            return \sprintf('function@%s()', $file);
        }

        return \sprintf('function@%s:%d()', $file, $line);
    }

    if ($function instanceof \ReflectionMethod) {
        return \sprintf('%s::%s()', formatClass($function->class), $function->name);
    }

    $class = $function->getClosureCalledClass();

    if ($class !== null) {
        return \sprintf('%s::%s()', formatReflectedClass($class), $function->name);
    }

    return $function->name . '()';
}
