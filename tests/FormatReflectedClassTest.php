<?php

declare(strict_types=1);

namespace Typhoon\Formatter;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;

#[CoversFunction('Typhoon\Formatter\formatReflectedClass')]
final class FormatReflectedClassTest extends TestCase
{
    public function testFormatReflectedClass(): void
    {
        $expectedFormattedClass = formatReflectedClass(new \ReflectionClass(\ReflectionClass::class));

        $formatted = formatClass(\ReflectionClass::class);

        self::assertSame($formatted, $expectedFormattedClass);
    }
}
