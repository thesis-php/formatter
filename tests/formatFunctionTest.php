<?php

namespace Typhoon\Formatter;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;

#[CoversFunction('Typhoon\Formatter\formatFunction')]
final class formatFunctionTest extends TestCase
{
    public function testCallableStringFunctionAppliesBrackets(): void
    {
        $this->assertSame('strlen()', formatFunction('strlen'));
    }

    public function testCallableStringNamespacedFunctionAppliesBrackets(): void
    {
        $this->assertSame(
            'Foo\\Bar\\baz()',
            formatFunction('Foo\\Bar\\baz')
        );
    }

    public function testCallableStringStaticMethodAppliesBracketsLikeStatic(): void
    {
        $input = self::class . '::testCallableStringStaticMethod';
        $this->assertSame(
            $input . '()',
            formatFunction($input)
        );
    }

    public function testCallableArrayStaticAppliesBracketsLikeStatic(): void
    {
        $this->assertSame(
            self::class . '::testCallableArrayStatic()',
            formatFunction([self::class, 'testCallableArrayStatic'])
        );
    }

    public function testCallableArrayInstanceAppliesBracketsLikeInstance(): void
    {
        $obj = new class {
            public function foo(): void {}
        };

        $this->assertMatchesRegularExpression(
            '/^class@.+\\.php:\\d+::foo\\(\\)$/',
            formatFunction([$obj, 'foo'])
        );
    }

    public function testCallableClosureReturnsWithRightFormat(): void
    {
        $c = function () { return 123; };
        $this->assertMatchesRegularExpression('/^[\w\\\\]+::[\w\\\\]+\{closure}\(\)$/', formatFunction($c));
    }

    public function testCallableEvalClosureWithRightFormat(): void
    {
        $c = eval('return function() {};');

        $this->assertMatchesRegularExpression('/^function@.+\\.php:\\d+\\(\\)$/', formatFunction($c));
    }

    public function testCallableInvokableWithRightFormat(): void
    {
        $obj = new class {
            public function __invoke() {}
        };
        $out = formatFunction($obj);
        $this->assertMatchesRegularExpression(
            '/^class@.+\\.php:\\d+\\(\\)$/',
            $out
        );
    }
}