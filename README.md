# Typhoon Formatter

[![PHP Version Requirement](https://img.shields.io/packagist/dependency-v/typhoon/formatter/php)](https://packagist.org/packages/typhoon/formatter)
[![GitHub Release](https://img.shields.io/github/v/release/typhoon-php/formatter)](https://github.com/typhoon-php/formatter/releases)
[![Code Coverage](https://codecov.io/gh/typhoon-php/formatter/branch/0.1.x/graph/badge.svg)](https://codecov.io/gh/typhoon-php/formatter/tree/0.1.x)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat\&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Ftyphoon-php%2Fformatter%2F0.1.x)](https://dashboard.stryker-mutator.io/reports/github.com/typhoon-php/formatter/0.1.x)

Typhoon Formatter is a zero‑dependency PHP utility that turns values, callables, reflection objects, and types into short, human‑readable strings.
It is designed for test frameworks, debuggers, and logging systems that need precise yet concise “labels” for arbitrary PHP values.

## Why Typhoon Formatter?

- **Universal input** — accepts anything: scalars, arrays, resources, objects, closures, enums & reflection artefacts.
- **Deterministic output** — every value is mapped to a predictable single‑line string; perfect for snapshots & assertion messages.
- **Context‑aware** — Anonymous classes / closures are annotated with the file & line where they were declared.
- **Tiny & safe** — immutable functions, no runtime side‑effects; works in strict mode and on production.

## Installation

```shell
composer require typhoon/formatter
```

The library exposes a set of **pure functions** under the `Typhoon\Formatter` namespace.
You may import only what you need or rely on the umbrella `format()` helper.

## Basic usage

```php
use function Typhoon\Formatter\format;

$payload = [
    'id'   => 42,
    'name' => 'Neo',
    'tags' => ['chosen', 'matrix'],
];

echo format($payload); // array{id: 42, name: 'Neo', tags: list{'chosen', 'matrix'}}
```

### Typical outputs
*(Paths & line numbers will vary.)*

```php
use function Typhoon\Formatter\format;

format(null); // null
format(true); // true
format(123); // 123
format(1.23); // 1.23
format(['a' => 1, 'b' => 2]); // array{a: 1, b: 2}
format([1, 2, 3]); // list{1, 2, 3}
format(new stdClass()); // stdClass
format(new class {}); // class@/path/to/File.php:12
format(static fn () => null); // function@/path/to/File.php:34()
enum Color { case RED; }
format(Color::RED); // My\Enum\Color::RED
format(fopen('php://memory', 'r')); // resource
```

## API reference

### `format(mixed $value): string`

Top‑level convenience helper. Delegates to the specialised formatters below.

```php
use function Typhoon\Formatter\format;

format(127);                   // '127'
format(['a' => 1]);            // 'array{a: 1}'
format(new DateTimeImmutable); // 'DateTimeImmutable'
```

### Class helpers

These functions return a fully-qualified class name, or Class@file:line for anonymous classes.

```php
use function Typhoon\Formatter\formatClass;
use function Typhoon\Formatter\formatReflectedClass;

// From an object or a class-string
formatClass(new stdClass()); // 'stdClass'
formatClass(stdClass::class); // 'stdClass'
formatClass(new class {}); // 'class@/path/to/File.php:88'

// From a ReflectionClass instance
formatReflectedClass(new ReflectionClass(stdClass::class)); // 'stdClass'
```

### Function helpers
These functions format callables and reflection functions into a readable string.

```php
use function Typhoon\Formatter\formatFunction;
use function Typhoon\Formatter\formatReflectedFunction;

// From a callable
formatFunction('trim'); // 'trim()'
formatFunction([MyClass::class, 'myMethod']); // 'MyClass::myMethod()'
formatFunction(fn() => null); // 'function@/path/to/File.php:42()'

// From a ReflectionFunctionAbstract instance
$reflectionFunction = new ReflectionFunction('substr');
formatReflectedFunction($reflectionFunction); // 'substr()'
```

### Property helpers
These functions create a string representation for class properties.

```php
use function Typhoon\Formatter\formatProperty;
use function Typhoon\Formatter\formatReflectedProperty;

$object = new class { public int $x = 10; };

// From an object instance and property name
formatProperty($object, 'x'); // 'class@/path/to/File.php:10::$x'

// From a ReflectionProperty instance
$reflectionProperty = new ReflectionProperty(ReflectionProperty::class, 'name');
formatReflectedProperty($reflectionProperty); // 'ReflectionProperty::$name'
```

### Parameter helpers
These functions create a string representation for function or method parameters.

```php
use function Typhoon\Formatter\formatParameter;
use function Typhoon\Formatter\formatReflectedParameter;

$closure = static function (int $paramName): void {};

// From a callable and parameter name
formatParameter($closure, 'paramName'); // 'function@/path/to/File.php:55($paramName)'

// From a ReflectionParameter instance
$reflectionParameter = new ReflectionParameter($closure, 'paramName');
formatReflectedParameter($reflectionParameter); // 'function@/path/to/File.php:55($paramName)'
```
## Best practices

* **Prefer the reflection variants** when reflection is already at hand — they avoid re‑instantiating reflection objects internally.
* When formatting **closures or anonymous classes**, keep in mind that the produced `file:line` refers to the place **where they were *declared***, not called.
* Avoid leaking the output into an untrusted context; it may include absolute file paths that reveal your directory structure.

