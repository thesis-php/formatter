<?php

namespace Typhoon\Formatter;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;

#[CoversFunction('Typhoon\Formatter\formatReflectedClass')]
final class formatReflectedClassTest extends TestCase
{
    public function testThatResultIsSame(): void
    {
        $reflected = formatReflectedClass(new \ReflectionClass(\ReflectionClass::class));

        $original = formatClass(\ReflectionClass::class);

        $this->assertSame($original, $reflected);
    }
}