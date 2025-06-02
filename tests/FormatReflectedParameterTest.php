<?php

declare(strict_types=1);

namespace Typhoon\Formatter;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;

#[CoversFunction('Typhoon\Formatter\formatReflectedParameter')]
final class FormatReflectedParameterTest extends TestCase
{
    /**
     * @throws \ReflectionException
     */
    public function testFormatReflectedParameter(): void
    {
        $expectedFormattedParameter = \sprintf('function@%s:%d($x)', __FILE__, __LINE__ + 2);

        $formatted = formatReflectedParameter(new \ReflectionParameter(static function ($x): void {}, 'x'));

        self::assertSame($expectedFormattedParameter, $formatted);
    }
}
