<?php

namespace Typhoon\Formatter;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;

#[CoversFunction('Typhoon\Formatter\formatParameter')]
final class formatParameterTest extends TestCase
{
    public function testThatParameterOfNamedFunctionAppliesRightWithInt(): void
    {
        $formatted = formatParameter('trim', 0);
        $this->assertSame('trim($0)', $formatted);
    }

    public function testThatParameterOfNamedFunctionAppliesRightWithString(): void
    {
        $formatted = formatParameter('trim', 'param');
        $this->assertSame('trim($param)', $formatted);
    }

    public function testThatParameterOfCallableAppliesRight(): void
    {
        $formatted = formatParameter([self::class, 'helper'], 'x');
        $this->assertMatchesRegularExpression('/^[\w\\\\]+::[\w\\\\]+\(\$\w+\)$/', $formatted);
    }

    public function testThatParameterOfClosureAppliesRight(): void
    {
        $closure = function () {};
        $formatted = formatParameter($closure, 'val');

        $this->assertMatchesRegularExpression('/^[\w\\\\]+::[\w\\\\]+\{closure}\(\$\w+\)$/', $formatted);
    }

    public function testThatParameterOfInvokableAppliesRight(): void
    {
        $invokable = new class {
            public function __invoke(array $data) {}
        };

        $formatted = formatParameter($invokable, 'data');
        $this->assertMatchesRegularExpression('/^class@.+\.php:\d+\(\$\w+\)$/', $formatted);
    }
}