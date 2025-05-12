<?php

namespace Typhoon\Formatter;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;

#[CoversFunction('Typhoon\Formatter\formatReflectedParameter')]
final class formatReflectedParameterTest extends TestCase
{
    public function testFormatsGlobalFunctionParameter(): void
    {
        $refFunc = new \ReflectionFunction('Typhoon\Formatter\formatReflectedParameter');
        $param = $refFunc->getParameters()[0];

        $formatted = formatReflectedParameter($param);

        $this->assertMatchesRegularExpression('/^[\w\\\\]+\(\$\w+\)$/', $formatted);
    }

    /**
     * @throws \ReflectionException
     */
    public function testFormatsClosureParameter(): void
    {
        $closure = static function (int $x) {};
        $ref = new \ReflectionFunction($closure);
        $param = $ref->getParameters()[0];

        $formatted = formatReflectedParameter($param);

        $this->assertMatchesRegularExpression('/^[\w\\\\]+::[\w\\\\]+\{closure}\(\$\w+\)$/', $formatted);
    }

    public function testFormatsClassMethodParameter(): void
    {
        $refMethod = new \ReflectionMethod(self::class, 'methodWithParam');
        $param = $refMethod->getParameters()[0];

        $formatted = formatReflectedParameter($param);

        $this->assertSame(self::class . '::methodWithParam($val)', $formatted);
    }

    public static function methodWithParam(int $val): void
    {}
}