<?php

namespace Typhoon\Formatter;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;

#[CoversFunction('Typhoon\Formatter\formatReflectedFunction')]
final class formatReflectedFunctionTest extends TestCase
{
    /**
     * @throws \ReflectionException
     */
    public function testFormatReflectionMethodAsStaticCall(): void
    {
        $ref = new \ReflectionMethod($this, 'helperMethod');
        $expected = self::class . '::helperMethod()';

        $this->assertSame($expected, formatReflectedFunction($ref));
    }

    public function testFormatNamedFunction(): void
    {
        $ref = new \ReflectionFunction('trim');
        $this->assertSame('trim()', formatReflectedFunction($ref));
    }

    public function testReturnPlainFunctionWhenFileNameUnavailable(): void
    {
        $mock = $this->getMockBuilder(\ReflectionFunction::class)
            ->setConstructorArgs(['Typhoon\Formatter\formatReflectedFunction'])
            ->getMock();
        $mock->method('getName')->willReturn('{closure}');
        $mock->method('getFileName')->willReturn(false);

        $formatted = formatReflectedFunction($mock);

        $this->assertSame('function()', $formatted);
    }

    public function testFallbackToFunctionWithPathWhenStartLineUnavailable(): void
    {
        $mock = $this->getMockBuilder(\ReflectionFunction::class)
            ->setConstructorArgs(['Typhoon\Formatter\formatReflectedFunction'])
            ->getMock();
        $mock->method('getName')->willReturn('{closure}');
        $mock->method('getFileName')->willReturn('Path');
        $mock->method('getStartLine')->willReturn(false);

        $formatted = formatReflectedFunction($mock);

        $this->assertSame(sprintf('function@%s()', $mock->getFileName()), $formatted);
    }

    public function testIncludePathAndLineWhenBothAvailable(): void
    {
        $mock = $this->getMockBuilder(\ReflectionFunction::class)
            ->setConstructorArgs(['Typhoon\Formatter\formatReflectedFunction'])
            ->getMock();
        $mock->method('getName')->willReturn('{closure}');
        $mock->method('getFileName')->willReturn('Path');
        $mock->method('getStartLine')->willReturn(5);

        $formatted = formatReflectedFunction($mock);

        $this->assertSame(sprintf('function@%s:%d()', $mock->getFileName(), $mock->getStartLine()), $formatted);
    }

    /**
     * @throws \ReflectionException
     */
    public function testFormatBoundClosureAsClassMethod(): void
    {
        $closure = \Closure::fromCallable([$this, 'helperMethod']);
        $ref = new \ReflectionFunction($closure);
        $formatted = formatReflectedFunction($ref);

        $this->assertSame(__CLASS__ . '::helperMethod()', $formatted);
    }

    /**
     * @throws \ReflectionException
     */
    public function testFormatGlobalFunctionWithNamespace(): void
    {
        $ref = new \ReflectionFunction(__NAMESPACE__ . '\formatReflectedFunction');
        $formatted = formatReflectedFunction($ref);

        $this->assertSame('Typhoon\Formatter\formatReflectedFunction()', $formatted);
    }

    /**
     * @throws \ReflectionException
     */
    public function testEvalClosureUsesFallbackPatternWhenNoStartLine(): void
    {
        $closure = eval('return function() { return 123; };');
        $ref = new \ReflectionFunction($closure);

        if ($ref->getStartLine() === false) {
            $formatted = formatReflectedFunction($ref);
            $this->assertMatchesRegularExpression('/^function@.+\(\)$/', $formatted);
        } else {
            $this->markTestSkipped('getStartLine() is available, skipping fallback branch');
        }
    }

    /**
     * @throws \ReflectionException
     */
    public function testEvalClosureStartsWithFunctionAt(): void
    {
        $closure = eval('return function () {};');
        $ref = new \ReflectionFunction($closure);
        $formatted = formatReflectedFunction($ref);

        $this->assertStringStartsWith('function@', $formatted);
    }

    public function helperMethod(): void
    {}
}