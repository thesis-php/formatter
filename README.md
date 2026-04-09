# Typhoon Formatter

[![PHP Version Requirement](https://img.shields.io/packagist/dependency-v/typhoon/formatter/php)](https://packagist.org/packages/typhoon/formatter)
[![GitHub Release](https://img.shields.io/github/v/release/typhoon-php/formatter)](https://github.com/typhoon-php/formatter/releases)
[![Code Coverage](https://codecov.io/gh/typhoon-php/formatter/branch/0.1.x/graph/badge.svg)](https://codecov.io/gh/typhoon-php/formatter/tree/0.1.x)
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Ftyphoon-php%2Fformatter%2F0.1.x)](https://dashboard.stryker-mutator.io/reports/github.com/typhoon-php/formatter/0.1.x)

A collection of functions for formatting PHP values and code elements into human-readable strings.
Useful for error messages, exceptions, and debugging output.

## Installation

```shell
composer require typhoon/formatter
```

## Functions

All functions are in the `Typhoon\Formatter` namespace.

### `format(mixed $value): string`

Formats any PHP value.

```php
format(null);                 // null
format(true);                 // true
format(42);                   // 42
format("it's fine");          // 'it\'s fine'
format([1, 2, 3]);            // list{1, 2, 3}
format(['a' => 1, 'b' => 2]); // array{a: 1, b: 2}
format((object)['x' => 1]);   // object{x: 1}
format(Status::Active);       // Status::Active
format(new MyClass());        // MyClass
format(fn() => null);         // function@src/foo.php:12()
```

### `formatClass(string|object $class): string`

Formats a class name. Anonymous classes get a readable `file:line` label.

```php
formatClass(MyClass::class); // MyClass
formatClass(new MyClass());  // MyClass
formatClass(new class {});   // class@src/foo.php:42
```

### `formatFunction(callable $function): string`

Formats a callable.

```php
formatFunction('strlen');                       // strlen()
formatFunction('MyClass::myMethod');            // MyClass::myMethod()
formatFunction([MyClass::class, 'myMethod']);   // MyClass::myMethod()
formatFunction(fn() => null);                   // function@src/foo.php:12()
formatFunction(new MyInvokable());              // MyInvokable()
```

### `formatParameter(callable $function, int|string $parameter): string`

Formats a parameter reference by index or name.

```php
formatParameter('myFunction', 'value');  // myFunction($value)
formatParameter('myFunction', 0);        // myFunction($0)
```

### `formatProperty(string|object $class, string $property): string`

Formats a property reference.

```php
formatProperty(MyClass::class, 'name');  // MyClass::$name
formatProperty(new MyClass(), 'name');   // MyClass::$name
```

### Reflection-based functions

Convenient wrappers around the functions above.

| Function                                                         | Example output               |
|------------------------------------------------------------------|------------------------------|
| `formatReflectedClass(\ReflectionClass $class)`                  | `MyClass`                    |
| `formatReflectedFunction(\ReflectionFunctionAbstract $function)` | `MyClass::myMethod()`        |
| `formatReflectedParameter(\ReflectionParameter $parameter)`      | `MyClass::myMethod($param)`  |
| `formatReflectedProperty(\ReflectionProperty $property)`         | `MyClass::$name`             |
| `formatReflectedType(?\ReflectionType $type)`                    | `string\|int`, `?Foo`, `A&B` |
