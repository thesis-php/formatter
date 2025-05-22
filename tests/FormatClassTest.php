<?php

declare(strict_types=1);

namespace Typhoon\Formatter;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversFunction('Typhoon\Formatter\formatClass')]
#[CoversFunction('Typhoon\Formatter\formatReflectedClass')]
final class FormatClassTest extends TestCase
{
    /**
     * @return \Generator<string, array{string|object, non-empty-string}>
     */
    public static function cases(): \Generator
    {
        yield 'from object' => [new \stdClass(), \stdClass::class];
        yield 'from class-string' => [\stdClass::class, \stdClass::class];
        yield 'from anonymous object' => [new class {}, \sprintf('class@%s:%d', __FILE__, __LINE__)];
        yield 'from extended class' => [new class extends \ArrayObject {}, \sprintf('ArrayObject@%s:%d', __FILE__, __LINE__)];
        yield 'from implemented class' => [
            new class implements \IteratorAggregate {
                public function getIterator(): \Traversable
                {
                    return new \ArrayIterator();
                }
            },
            \sprintf('IteratorAggregate@%s:%d', __FILE__, __LINE__ - 6),
        ];
        yield 'from eval object' => [(object) eval('return new \stdClass();'), \stdClass::class];
        yield 'from eval anonymous object' => [(object) eval('return new class {};'), \sprintf('class@%s:%d', __FILE__, __LINE__)];
    }

    /**
     * @param class-string|object $class
     * @param non-empty-string $expectedFormattedClass
     */
    #[DataProvider('cases')]
    public function testFormatClass(string|object $class, string $expectedFormattedClass): void
    {
        $formatted = formatClass($class);

        self::assertSame($expectedFormattedClass, $formatted);
    }

    /**
     * @param class-string|object $class
     * @param non-empty-string $expectedFormattedClass
     * @throws \ReflectionException
     */
    #[DataProvider('cases')]
    public function testFormatReflectedClass(string|object $class, string $expectedFormattedClass): void
    {
        $formatted = formatReflectedClass(new \ReflectionClass($class));

        self::assertSame($expectedFormattedClass, $formatted);
    }
}
