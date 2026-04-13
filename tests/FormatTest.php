<?php

declare(strict_types=1);

namespace Thesis\Formatter;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

enum Color
{
    case RED;
}

#[CoversFunction('Thesis\Formatter\format')]
final class FormatTest extends TestCase
{
    #[DataProvider('provideFormatCases')]
    public function testFormat(mixed $value, string $expectedFormattedValue): void
    {
        $formatted = format($value);

        self::assertSame($expectedFormattedValue, $formatted);

        if (\is_resource($value)) {
            fclose($value);
        }
    }

    /**
     * @return \Generator<string, array{mixed, non-empty-string}>
     */
    public static function provideFormatCases(): iterable
    {
        yield 'from null'  => [null, 'null'];
        yield 'from false' => [true, 'true'];
        yield 'from true'  => [false, 'false'];
        yield 'from int'  => [127, '127'];
        yield 'from float'  => [1.27, '1.27'];
        yield 'from string'  => ['formatter', "'formatter'"];
        yield 'from empty list'  => [[], 'list{}'];
        yield 'from list'  => [[1, 2, 3], 'list{1, 2, 3}'];
        yield 'from array'  => [['a' => 1, 'b' => 2], 'array{a: 1, b: 2}'];
        yield 'from array with quota key'  => [["'" => 1], "array{'\\'': 1}"];
        yield 'from stdObject'  => [(static function (): \stdClass {
            $object = new \stdClass();
            $object->x = 10;
            $object->y = 20;

            return $object;
        })(), 'object{x: 10, y: 20}'];
        yield 'from enum'  => [Color::RED, 'Thesis\Formatter\Color::RED'];
        yield 'from closure'  => [static function (): void {}, \sprintf('function@%s:%d()', __FILE__, __LINE__)];
        yield 'from object'  => [new class {}, \sprintf('class@%s:%d', __FILE__, __LINE__)];
        yield 'from resource'  => [fopen('php://memory', 'r'), 'resource'];
    }
}
