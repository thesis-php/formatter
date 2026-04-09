<?php

declare(strict_types=1);

namespace Typhoon\Formatter;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversFunction('Typhoon\Formatter\formatReflectedType')]
final class FormatReflectedTypeTest extends TestCase
{
    #[DataProvider('provideFormatReflectedTypeCases')]
    public function testFormatReflectedType(?\ReflectionType $type, string $expectedFormattedReflectionType): void
    {
        $formatted = formatReflectedType($type);

        self::assertSame($expectedFormattedReflectionType, $formatted);
    }

    /**
     * @return \Generator<string, array{null|\ReflectionType, string}>
     * @throws \ReflectionException
     */
    public static function provideFormatReflectedTypeCases(): iterable
    {
        yield 'from null' => [null, ''];
        yield 'from exact type' => [
            (new \ReflectionFunction(static fn(int $a) => null))->getParameters()[0]->getType(), // @phpstan-ignore offsetAccess.notFound
            'int',
        ];
        yield 'from nullable type' => [
            (new \ReflectionFunction(static fn(?int $a) => null))->getParameters()[0]->getType(), // @phpstan-ignore offsetAccess.notFound
            '?int',
        ];
        yield 'from union type' => [
            (new \ReflectionFunction(static fn(string|int $a) => null))->getParameters()[0]->getType(), // @phpstan-ignore offsetAccess.notFound
            'string|int',
        ];
        yield 'from intersection type' => [
            (new \ReflectionFunction(static fn(\IteratorAggregate&\Iterator $a) => null))->getParameters()[0]->getType(), // @phpstan-ignore offsetAccess.notFound
            'IteratorAggregate&Iterator',
        ];
    }
}
