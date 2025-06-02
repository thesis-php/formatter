<?php

declare(strict_types=1);

namespace Typhoon\Formatter;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversFunction('Typhoon\Formatter\formatFunction')]
final class FormatFunctionTest extends TestCase
{
    /**
     * @return \Generator<string, array{callable, non-empty-string}>
     */
    public static function cases(): \Generator
    {
        yield 'brackets to string with ::' => [
            \sprintf('%s::cases', self::class),
            \sprintf('%s::cases()', self::class),
        ];
        yield 'brackets to string' => [
            'strlen',
            'strlen()',
        ];
        yield 'from array' => [
            [
                new class {
                    public function foo(): void {}
                },
                'foo',
            ],
            \sprintf('class@%s:%d::foo()', __FILE__, __LINE__ - 5),
        ];
        yield 'from closure' => [
            static function (): void {},
            \sprintf('function@%s:%d()', __FILE__, __LINE__ - 1),
        ];
        yield 'from invokable object' => [
            new class {
                public function __invoke(): void {}
            },
            \sprintf('class@%s:%d()', __FILE__, __LINE__ - 3),
        ];
        yield 'from eval closure' => [
            (static function (): callable {
                /**
                 * @var callable $closure
                 */
                $closure = eval('return function() {};');

                return $closure;
            })(),
            \sprintf('function@%s:%d()', __FILE__, __LINE__ - 4),
        ];
    }

    /**
     * @param callable $closure
     */
    #[DataProvider('cases')]
    public function testFormatFunction(mixed $closure, string $expectedFormattedFunction): void
    {
        $formatted = formatFunction($closure);

        self::assertSame($expectedFormattedFunction, $formatted);
    }
}
