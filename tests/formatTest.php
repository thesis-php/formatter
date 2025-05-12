<?php

namespace Typhoon\Formatter;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;

enum Color { case RED; }

#[CoversFunction('Typhoon\Formatter\format')]
final class formatTest extends TestCase
{
    public function testNull(): void
    {
        $this->assertSame('null', format(null));
    }

    public function testTrue(): void
    {
        $this->assertSame('true', format(true));
    }

    public function testFalse(): void
    {
        $this->assertSame('false', format(false));
    }

    public function testInt(): void
    {
        $this->assertSame('42', format(42));
    }

    public function testFloat(): void
    {
        $this->assertSame('3.14', format(3.14));
    }

    public function testEmptyString(): void
    {
        $this->assertSame("''", format(''));
    }

    public function testStringWithQuote(): void
    {
        $this->assertSame("'don\\'t'", format("don't"));
    }

    public function testEmptyArray(): void
    {
        $this->assertSame('list{}', format([]));
    }

    public function testListArray(): void
    {
        $this->assertSame('list{1, 2, 3}', format([1, 2, 3]));
    }

    public function testAssocArray(): void
    {
        $this->assertSame('array{a: 1, b: 2}', format(['a' => 1, 'b' => 2]));
    }

    public function testAssocArrayWithQuoteKey(): void
    {
        $this->assertSame("array{'a\\'b': 1}", format(["a'b" => 1]));
    }

    public function testStdClassObject(): void
    {
        $obj = new \stdClass();
        $obj->x = 10;
        $obj->y = 20;

        $this->assertSame('object{x: 10, y: 20}', format($obj));
    }

    public function testStdClassWithQuotedProperty(): void
    {
        $obj = new \stdClass();
        $obj->{"a'b"} = 123;

        $this->assertSame("object{'a\\'b': 123}", format($obj));
    }

    public function testUnitEnum(): void
    {
        $this->assertSame('Typhoon\Formatter\Color::RED', format(Color::RED));
    }

    public function testClosure(): void
    {
        $fn = function () {};
        $formatted = format($fn);

        $this->assertStringContainsString('()', $formatted);
    }

    public function testObject(): void
    {
        $obj = new class {};
        $this->assertStringEndsWith('.php:'. __LINE__ - 1, format($obj));
    }

    public function testResource(): void
    {
        $res = fopen('php://memory', 'rb');
        $this->assertSame('resource', format($res));
        fclose($res);
    }
}