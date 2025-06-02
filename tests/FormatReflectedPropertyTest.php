<?php


declare(strict_types=1);

namespace Typhoon\Formatter;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;

#[CoversFunction('Typhoon\Formatter\formatReflectedProperty')]
final class FormatReflectedPropertyTest extends TestCase
{
    public function testFormatReflectedProperty(): void
    {
        $expectedFormattedProperty = 'ReflectionProperty::$name';

        $formatted = formatReflectedProperty(new \ReflectionProperty(\ReflectionProperty::class, 'name'));

        self::assertSame($expectedFormattedProperty, $formatted);
    }
}
