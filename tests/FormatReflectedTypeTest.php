<?php

declare(strict_types=1);

namespace Thesis\Formatter;

use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversFunction('Thesis\Formatter\formatReflectedType')]
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
            // @phpstan-ignore offsetAccess.notFound
            (new \ReflectionFunction(static fn(int $a) => null))->getParameters()[0]->getType(),
            'int',
        ];
        yield 'from nullable type' => [
            // @phpstan-ignore offsetAccess.notFound
            (new \ReflectionFunction(static fn(?int $a) => null))->getParameters()[0]->getType(),
            '?int',
        ];
        yield 'from union type' => [
            // @phpstan-ignore offsetAccess.notFound
            (new \ReflectionFunction(static fn(string|int $a) => null))->getParameters()[0]->getType(),
            'string|int',
        ];
        yield 'from intersection type' => [
            // @phpstan-ignore offsetAccess.notFound
            (new \ReflectionFunction(static fn(\IteratorAggregate&\Iterator $a) => null))->getParameters()[0]->getType(),
            'IteratorAggregate&Iterator',
        ];
    }
}
