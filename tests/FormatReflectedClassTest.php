<?php

declare(strict_types=1);

namespace Thesis\Formatter;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;

#[CoversFunction('Thesis\Formatter\formatReflectedClass')]
final class FormatReflectedClassTest extends TestCase
{
    public function testFormatReflectedClass(): void
    {
        $class = new \ReflectionClass(\ReflectionClass::class);
        $expectedFormattedClass = formatReflectedClass($class);

        $formatted = formatClass(\ReflectionClass::class);

        self::assertSame($expectedFormattedClass, $formatted);
    }
}
