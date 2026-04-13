<?php

declare(strict_types=1);

namespace Thesis\Formatter;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;

#[CoversFunction('Thesis\Formatter\formatProperty')]
#[CoversFunction('Thesis\Formatter\formatReflectedProperty')]
final class FormatPropertyTest extends TestCase
{
    public function testFormatProperty(): void
    {
        $expectedFormattedProperty = \sprintf('class@%s:%d::$x', __FILE__, __LINE__ + 2);

        $formatted = formatProperty(new class {
            public int $x = 0;
        }, 'x');

        self::assertSame($expectedFormattedProperty, $formatted);
    }
}
