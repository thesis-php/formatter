# Typhoon Formatter

---

[![PHP Version Requirement](https://img.shields.io/packagist/dependency-v/typhoon/formatter/php)](https://packagist.org/packages/typhoon/formatter)
[![GitHub Release](https://img.shields.io/github/v/release/typhoon-php/formatter)](https://github.com/typhoon-php/formatter/releases)
[![Code Coverage](https://codecov.io/gh/typhoon-php/formatter/branch/0.1.x/graph/badge.svg)](https://codecov.io/gh/typhoon-php/formatter/tree/0.1.x)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat\&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Ftyphoon-php%2Fformatter%2F0.1.x)](https://dashboard.stryker-mutator.io/reports/github.com/typhoon-php/formatter/0.1.x)

**Typhoon Formatter** is a zero‑dependency PHP utility that turns values, callables, reflection objects, and types into short, human‑readable strings.
It is designed for test frameworks, debuggers, and logging systems that need precise yet concise “labels” for arbitrary PHP values.


## Why Typhoon Formatter?

---

* **Universal input** — accepts *anything*: scalars, arrays, resources, objects, closures, enums & reflection artefacts.
* **Deterministic output** — every value is mapped to a predictable single‑line string; perfect for snapshots & assertion messages.
* **Context‑aware** — Anonymous classes / closures are annotated with the **file & line** where they were declared.
* **Tiny & safe** — immutable functions, **no runtime side‑effects**; works in strict mode and on production.

## Installation

---

```shell
composer require typhoon/formatter
```

The library exposes a set of **pure functions** under the `Typhoon\Formatter` namespace.
You may import only what you need or rely on the umbrella `format()` helper.


## Basic usage

---

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

---

| Input value                             | `format()` output example      |
|-----------------------------------------|--------------------------------|
| `null`                                  | `null`                         |
| `true / false`                          | `true` / `false`               |
| `123`, `1.23`                           | `123`, `1.23`                  |
| `['a' => 1, 'b' => 2]`                  | `array{a: 1, b: 2}`            |
| `[1, 2, 3]`                             | `list{1, 2, 3}`                |
| `new stdClass()`                        | `class@/path/File.php:12`      |
| `static fn () => null`                  | `function@/path/File.php:34()` |
| `Color::RED` (enum case)                | `My\Enum\Color::RED`           |
| `fopen('php://memory', 'r')` (resource) | `resource`                     |

*(Paths & line numbers obviously vary.)*

## API reference

---

### `format(mixed $value): string`

Top‑level convenience helper. Delegates to the specialised formatters below.

```php
use function Typhoon\Formatter\format;

format(127);                   // '127'
format(['a' => 1]);            // 'array{a: 1}'
format(new DateTimeImmutable); // 'DateTimeImmutable'
```

### Class helpers

| Function                                    | Description                                                                             | Example                                                                   |                                               |
|---------------------------------------------|-----------------------------------------------------------------------------------------|---------------------------------------------------------------------------|-----------------------------------------------|
| `formatClass(object\|string $class)`        | Returns fully‑qualified class name or Class@file:line for anonymous / evaluated classes | `formatClass($obj)` → `class@/src/Foo.php:88`                             |
| `formatReflectedClass(ReflectionClass $rc)` | Same but from a `ReflectionClass`                                                       | `formatReflectedClass(new ReflectionClass(stdClass::class))` → `stdClass` |                                               |

### Function helpers

| Function                                                  | Input                                      | Output example                            |
|-----------------------------------------------------------|--------------------------------------------|-------------------------------------------|
| `formatFunction(callable $fn)`                            | closure, array callable, `foo`, `Bar::baz` | `Bar::baz()` or `function@/file.php:42()` |
| `formatReflectedFunction(ReflectionFunctionAbstract $rf)` | reflection of function / method / closure  | `Bar::baz()` or `function@/file.php:42()` |

### Property helpers

| Function                                          | Input                    | Output example              |
|---------------------------------------------------|--------------------------|-----------------------------|
| `formatProperty(object $object, string $name)`    | instance + property name | `class@/File.php:10::$name` |
| `formatReflectedProperty(ReflectionProperty $rp)` | reflection               | `ReflectionProperty::$name` |

### Parameter helpers

| Function                                            | Input                 | Output example                  |
|-----------------------------------------------------|-----------------------|---------------------------------|
| `formatParameter(callable $fn, string $param)`      | function + param name | `function@/File.php:55($param)` |
| `formatReflectedParameter(ReflectionParameter $rp)` | reflection            | `function@/File.php:55($param)` |

## Best practices

---

* **Prefer the reflection variants** when reflection is already at hand — they avoid re‑instantiating reflection objects internally.
* When formatting **closures or anonymous classes**, keep in mind that the produced `file:line` refers to the place **where they were *declared***, not called.
* Avoid leaking the output into an untrusted context; it may include absolute file paths that reveal your directory structure.


## Testing

---

Typhoon Formatter is covered by a thorough PHPUnit test‑suite (see `/tests`).
Run it locally with:

```shell
composer test
```
or
```shell
vendor/bin/phpunit
```
