<?php

namespace Typhoon\Formatter;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\TestCase;

#[CoversFunction('Typhoon\Formatter\formatReflectedProperty')]
final class formatReflectedPropertyTest extends TestCase
{
    public function testThatResultIsSame(): void
    {
        $reflected = formatReflectedProperty(new \ReflectionProperty(\ReflectionProperty::class, 'name'));

        $original = formatProperty(\ReflectionProperty::class, 'name');

        $this->assertSame($original, $reflected);
    }
}