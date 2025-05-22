<?php

declare(strict_types=1);

namespace Typhoon\Formatter;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversFunction('Typhoon\Formatter\formatReflectedFunction')]
final class FormatReflectedFunctionTest extends TestCase
{
    /**
     * @return \Generator<string, array{\ReflectionFunctionAbstract, non-empty-string}>
     * @throws \ReflectionException
     */
    public static function cases(): \Generator
    {
        yield 'from object method' => [
            new \ReflectionMethod(new self('name'), 'testFormatReflectedFunction'),
            self::class . '::testFormatReflectedFunction()',
        ];
        yield 'from class method' => [
            new \ReflectionMethod(new self('name'), 'cases'),
            self::class . '::cases()',
        ];
        yield 'from named method' => [new \ReflectionFunction('trim'), 'trim()'];
        yield 'from closure method' => [
            new \ReflectionFunction((new self('name'))->testFormatReflectedFunction(...)),
            \sprintf('%s::testFormatReflectedFunction()', __CLASS__),
        ];
        yield 'from eval closure method' => [
            (static function (): \ReflectionFunction {
                /**
                 * @var \Closure $closure
                 */
                $closure = eval('return function() {};');

                return new \ReflectionFunction($closure);
            })(),
            \sprintf('function@%s:%d()', __FILE__, __LINE__ - 4),
        ];
    }

    /**
     * @param non-empty-string $expectedFormattedFunction
     */
    #[DataProvider('cases')]
    public function testFormatReflectedFunction(\ReflectionFunctionAbstract $function, string $expectedFormattedFunction): void
    {
        $formatted = formatReflectedFunction($function);

        self::assertSame($expectedFormattedFunction, $formatted);
    }

    public function testFormatReflectedFunctionWhenFileNameIsUnavailable(): void
    {
        $mock = $this->createMock(\ReflectionFunction::class);
        $mock->method('getName')->willReturn('{closure}');
        $mock->method('getFileName')->willReturn(false);

        $formatted = formatReflectedFunction($mock);

        self::assertSame('function()', $formatted);
    }

    public function testFormatReflectedFunctionWhenStartLineIsUnavailable(): void
    {
        $mock = $this->createMock(\ReflectionFunction::class);
        $mock->method('getName')->willReturn('{closure}');
        $mock->method('getFileName')->willReturn('Path');
        $mock->method('getStartLine')->willReturn(false);
        $expectedFormattedFunction = \sprintf('function@%s()', $mock->getFileName());

        $formatted = formatReflectedFunction($mock);

        self::assertSame($expectedFormattedFunction, $formatted);
    }

    public function testFormatReflectedFunctionWhenAllAvailable(): void
    {
        $mock = $this->createMock(\ReflectionFunction::class);
        $mock->method('getName')->willReturn('{closure}');
        $mock->method('getFileName')->willReturn('Path');
        $mock->method('getStartLine')->willReturn(5);
        $expectedFormattedFunction = \sprintf('function@%s:%d()', $mock->getFileName(), $mock->getStartLine());

        $formatted = formatReflectedFunction($mock);

        self::assertSame($expectedFormattedFunction, $formatted);
    }
}
