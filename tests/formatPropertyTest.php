<?php

namespace Typhoon\Formatter;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;

#[CoversFunction('Typhoon\Formatter\formatProperty')]
#[CoversFunction('Typhoon\Formatter\formatReflectedProperty')]
final class formatPropertyTest extends TestCase
{
    public function testThatRegularClassNameAppliesParameterRight(): void
    {
        $formatted = formatProperty('App\\User', 'email');
        $this->assertSame('App\\User::$email', $formatted);
    }

    public function testThatAnonymousPatternInStringAppliesParameterRight(): void
    {
        $input = 'Foo\\Bar@anonymous' . "\x00" . '/tmp/file.php(42)';
        $this->assertSame('Foo\\Bar@/tmp/file.php:42::$x', formatProperty($input, 'x'));
    }

    public function testThatNormalObjectAppliesParameterRight(): void
    {
        $obj = new \stdClass();
        $expected = \stdClass::class . '::$id';
        $this->assertSame($expected, formatProperty($obj, 'id'));
    }

    public function testThatAnonymousObjectAppliesParameterRight(): void
    {
        $anon = new class() {};
        $formatted = formatProperty($anon, 'data');
        $this->assertMatchesRegularExpression('/^class@.+\.php:\d+::\$\w+$/', $formatted);
    }
}