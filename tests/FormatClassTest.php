<?php

declare(strict_types=1);

namespace Typhoon\Formatter;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;

#[CoversFunction('Typhoon\Formatter\formatClass')]
#[CoversFunction('Typhoon\Formatter\formatReflectedClass')]
final class FormatClassTest extends TestCase
{
    public function testObjectClassReturnsAsIs(): void
    {
        $thisClass = clone $this;

        self::assertSame($thisClass::class, formatClass($thisClass));
    }

    public function testStringClassReturnsAsIs(): void
    {
        $input = 'Foo\Bar\ClassName';
        self::assertSame($input, formatClass($input));
    }

    public function testStringWithAnonymousPatternAppliesAnonymousPattern(): void
    {
        $input = "Foo\Bar@anonymous\x00/tmp/file.php(123)";
        self::assertSame('Foo\Bar@/tmp/file.php:123', formatClass($input));
    }

    public function testStringWithoutX00DoesntAppliesAnonymousPattern(): void
    {
        $input = 'Foo\Bar@anonymous/tmp/file.php(123)';
        self::assertSame($input, formatClass($input));
    }

    public function testThatAnonymousClassAppliesAnonymousPattern(): void
    {
        $anonymousClass = new class {};
        $startLine = (new \ReflectionClass($anonymousClass))->getStartLine();


        self::assertSame(
            'class@/opt/project/tests/FormatClassTest.php:' . $startLine,
            formatClass($anonymousClass)
        );
    }

    /**
     * @throws \ReflectionException
     */
    public function testThatEvalDoesntAffectAnonymousClass(): void
    {
        $evalClass = (eval('return new class() {};'));
        $startLine = 56;

        self::assertSame(
            'class@/opt/project/tests/FormatClassTest.php:' . $startLine,
            formatClass($evalClass)
        );
    }

    public function testThatReflectionDoesntAffectAnonymousClass(): void
    {
        $anonymousClass = new class {};
        $reflectionClass = (new \ReflectionClass($anonymousClass));

        self::assertSame(
            'class@/opt/project/tests/FormatClassTest.php:' . $reflectionClass->getStartLine(),
            formatClass($reflectionClass->getName())
        );
    }
}
