<?php

declare(strict_types=1);

namespace Typhoon\Formatter;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversFunction('Typhoon\Formatter\formatReflectedType')]
final class FormatReflectedTypeTest extends TestCase
{
    /**
     * @return \Generator<string, array{null|\ReflectionType, string}>
     * @throws \ReflectionException
     */
    public static function cases(): \Generator
    {
        yield 'from null' => [null, ''];
        yield 'from exact type' => [
            (new \ReflectionFunction(static fn(int $a) => null))->getParameters()[0]->getType(),
            'int',
        ];
        yield 'from nullable type' => [
            (new \ReflectionFunction(static fn(?int $a) => null))->getParameters()[0]->getType(),
            '?int',
        ];
        yield 'from union type' => [
            (new \ReflectionFunction(static fn(string|int $a) => null))->getParameters()[0]->getType(),
            'string|int',
        ];
        yield 'from intersection type' => [
            (new \ReflectionFunction(static fn(\IteratorAggregate&\Iterator $a) => null))->getParameters()[0]->getType(),
            'IteratorAggregate&Iterator',
        ];
    }

    #[DataProvider('cases')]
    public function testFormatReflectedType(?\ReflectionType $type, string $expectedFormattedReflectionType): void
    {
        $formatted = formatReflectedType($type);

        self::assertSame($expectedFormattedReflectionType, $formatted);
    }
}
