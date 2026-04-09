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
        /** @var \ReflectionClass<object> $reflectedClass */
        $reflectedClass = new \ReflectionClass(\ReflectionClass::class);
        $expectedFormattedClass = formatReflectedClass($reflectedClass);

        $formatted = formatClass(\ReflectionClass::class);

        self::assertSame($formatted, $expectedFormattedClass);
    }
}
