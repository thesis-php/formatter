<?php

namespace Typhoon\Formatter;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;

#[CoversFunction('Typhoon\Formatter\formatReflectedType')]
final class formatReflectedTypeTest extends TestCase
{
    public function testNullType(): void
    {
        $this->assertSame('', formatReflectedType(null));
    }

    /**
     * @throws \ReflectionException
     */
    public function testNamedNonNullableType(): void
    {
        $fn = function (int $a): void {return;};
        $param = new \ReflectionFunction($fn);
        $type = $param->getParameters()[0]->getType();

        $this->assertInstanceOf(\ReflectionNamedType::class, $type);
        $this->assertSame('int', formatReflectedType($type));
    }

    /**
     * @throws \ReflectionException
     */
    public function testNamedNullableType(): void
    {
        $fn = fn (?string $s) => null;
        $param = new \ReflectionFunction($fn);
        $type = $param->getParameters()[0]->getType();

        $this->assertInstanceOf(\ReflectionNamedType::class, $type);
        $this->assertSame('?string', formatReflectedType($type));
    }

    /**
     * @throws \ReflectionException
     */
    public function testNullableMixedReturnsWithoutQuestion(): void
    {
        $fn = eval('return fn (mixed $v) => null;');
        $param = new \ReflectionFunction($fn);
        $type = $param->getParameters()[0]->getType();

        $this->assertInstanceOf(\ReflectionNamedType::class, $type);
        $this->assertSame('mixed', formatReflectedType($type));
    }

    /**
     * @throws \ReflectionException
     */
    public function testNullableNullType(): void
    {
        $fn = eval('return fn (null $v) => null;');
        $param = new \ReflectionFunction($fn);
        $type = $param->getParameters()[0]->getType();

        $this->assertInstanceOf(\ReflectionNamedType::class, $type);
        $this->assertSame('null', formatReflectedType($type));
    }

    /**
     * @throws \ReflectionException
     */
    public function testUnionType(): void
    {
        $fn = eval('return fn (int|string $x) => null;');
        $ref = new \ReflectionFunction($fn);
        $type = $ref->getParameters()[0]->getType();

        $this->assertInstanceOf(\ReflectionUnionType::class, $type);
        $this->assertMatchesRegularExpression('/^(int|string)\|(?!\1)(int|string)$/', formatReflectedType($type));
    }

    /**
     * @throws \ReflectionException
     */
    public function testIntersectionType(): void
    {
        eval('interface A {}; interface B {};');
        $fn = eval('return function (A&B $x): void {};');
        $ref = new \ReflectionFunction($fn);
        $type = $ref->getParameters()[0]->getType();

        $this->assertInstanceOf(\ReflectionIntersectionType::class, $type);
        $this->assertSame('A&B', formatReflectedType($type));
    }
}