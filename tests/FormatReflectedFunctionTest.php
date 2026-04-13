<?php

declare(strict_types=1);

namespace Thesis\Formatter;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversFunction('Thesis\Formatter\formatReflectedFunction')]
final class FormatReflectedFunctionTest extends TestCase
{
    /**
     * @param non-empty-string $expectedFormattedFunction
     */
    #[DataProvider('provideFormatReflectedFunctionCases')]
    public function testFormatReflectedFunction(\ReflectionFunctionAbstract $function, string $expectedFormattedFunction): void
    {
        $formatted = formatReflectedFunction($function);

        self::assertSame($expectedFormattedFunction, $formatted);
    }

    /**
     * @return \Generator<string, array{\ReflectionFunctionAbstract, non-empty-string}>
     * @throws \ReflectionException
     */
    public static function provideFormatReflectedFunctionCases(): iterable
    {
        yield 'from object method' => [
            new \ReflectionMethod(new self('name'), 'testFormatReflectedFunction'),
            self::class . '::testFormatReflectedFunction()',
        ];
        yield 'from class method' => [
            new \ReflectionMethod(self::class, 'testFormatReflectedFunction'),
            self::class . '::testFormatReflectedFunction()',
        ];
        yield 'from named method' => [new \ReflectionFunction('trim'), 'trim()'];
        yield 'from closure method' => [
            new \ReflectionFunction((new self('name'))->testFormatReflectedFunction(...)),
            \sprintf('%s::testFormatReflectedFunction()', self::class),
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
