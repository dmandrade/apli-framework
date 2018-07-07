# Apli Support
       
Apli Support package provides some core useful tool set.

## Installation via Composer

Add this to the require block in your `composer.json`.

``` json
{
    "require": {
        "apli/support": "~1.0"
    }
}
```

## Enum

Enum support class implementation inspired from SplEnum

### Declaration

```php
use Apli\Support\Enum;

/**
 * State enum
 */
class State extends Enum
{
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';
    const EXPIRED = 'expired';
    const DESTROYED = 'destroyed';
    const ERROR = 'error';
}
```

You can declare the enum class using `final` or set the constant to `private` (only supported in PHP>7.1)

### Usage

```php
$state = new State(State::ACTIVE);
// or
$state = Action::ACTIVE();
```

Static methods are automatically implemented to provide quick access to an enum value.

As each constant is a class you have the advantage of type-hint:

```php
function setAction(Action $action) {
    // ...
}
```

### Complete list of methods

- `__toString()` You can `echo $state`, it will display the enum value (value of the constant)
- `getValue()` Returns the current value of the enum
- `getKey()` Returns the key of the current value on Enum
- `equals()` Tests whether enum instances are equal to another

Static methods:

- `getDefault()` Returns the default value of the enum or throw `UnexpectedValueException` if not exists
- `toArray()` returns all possible values as an array
- `keys()` Returns the names (keys) of all constants in the Enum class
- `values()` Returns instances of the Enum class of all Enum constants (constant name in key, Enum instance in value)
- `isValid()` Check if tested value is valid on enum set
- `exists()` Check if tested key is exists on enum set
- `search()` Return key for searched value

Note: constant `__default` value is only accessible using `getDefault()`, the other methods do not return this constant.

### Static methods

Static method helpers are implemented using [`__callStatic()`](http://www.php.net/manual/en/language.oop5.overloading.php#object.callstatic).

If you care about IDE autocompletion, you can use phpdoc (this is supported in PhpStorm for example):

```php
/**
 * Class State
 *
 * @method static State ACTIVE()
 * @method static State INACTIVE()
 */
class State extends Enum
{
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';
}
```
