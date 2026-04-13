<?php


declare(strict_types=1);

namespace Thesis\Formatter;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;

#[CoversFunction('Thesis\Formatter\formatParameter')]
final class FormatParameterTest extends TestCase
{
    public function testFormatParameter(): void
    {
        $expectedFormattedParameter = \sprintf('function@%s:%s($x)', __FILE__, __LINE__ + 2);

        $formatted = formatParameter(static function (int $x): void {}, 'x');

        self::assertSame($expectedFormattedParameter, $formatted);
    }
}
