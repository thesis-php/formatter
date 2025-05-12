<?php

namespace Typhoon\Formatter;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;

#[CoversFunction('Typhoon\Formatter\formatClass')]
#[CoversFunction('Typhoon\Formatter\formatReflectedClass')]
final class formatClassTest extends TestCase
{
    private static string $anonymousPattern  = '/^class@.+\.php:\d+$/';
    public function testObjectClassReturnsAsIs(): void
    {
        $thisClass = clone $this;

        $this->assertSame($thisClass::class, formatClass($thisClass));
    }

    public function testStringClassReturnsAsIs(): void
    {
        $input = 'Foo\\Bar\\ClassName';
        $this->assertSame($input, formatClass($input),);
    }

    public function testStringWithAnonymousPatternAppliesAnonymousPattern(): void
    {
        $input = 'Foo\\Bar@anonymous' . "\x00" . '/tmp/file.php(123)';
        $this->assertMatchesRegularExpression(self::$anonymousPattern, formatClass($input));
    }

    public function testStringWithoutX00DoesntAppliesAnonymousPattern(): void
    {
        $input = 'Foo\\Bar@anonymous/tmp/file.php(123)';
        $this->assertSame($input, formatClass($input),);
    }

    public function testThatAnonymousClassAppliesAnonymousPattern(): void
    {
        $anonymousClass = new class() {};

        $this->assertMatchesRegularExpression(self::$anonymousPattern, formatClass($anonymousClass));
    }

    public function testThatEvalAnonymousClassAppliesAnonymousPattern(): void
    {
        $evalClass = (eval('return new class() {};'));

        $this->assertMatchesRegularExpression(self::$anonymousPattern, formatClass($evalClass));
    }

    public function testThatReflectionAnonymousClassAppliesAnonymousPattern(): void
    {
        $anonymousClass = new class() {};
        $reflectionClassName = (new \ReflectionClass($anonymousClass))->getName();
        $this->assertMatchesRegularExpression(self::$anonymousPattern, formatClass($reflectionClassName));
    }
}