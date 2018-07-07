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
 * Action enum
 */
class Action extends Enum
{
    const VIEW = 'view';
    const EDIT = 'edit';
}
```

### Usage

```php
$action = new Action(Action::VIEW);
// or
$action = Action::VIEW();
```

As you can see, static methods are automatically implemented to provide quick access to an enum value.

One advantage over using class constants is to be able to type-hint enum values:

```php
function setAction(Action $action) {
    // ...
}
```

### Complete list of methods

- `__toString()` You can `echo $action`, it will display the enum value (value of the constant)
- `getValue()` Returns the current value of the enum
- `getKey()` Returns the key of the current value on Enum
- `equals()` Tests whether enum instances are equal to another

Static methods:

- `getDefault()` Returns the default value of the enum or throw `UnexpectedValueException` if not exists
- `toArray()` returns all possible values as an array (except `__default`)
- `keys()` Returns the names (keys) of all constants in the Enum class
- `values()` Returns instances of the Enum class of all Enum constants (constant name in key, Enum instance in value)
- `isValid()` Check if tested value is valid on enum set
- `exists()` Check if tested key is exists on enum set
- `search()` Return key for searched value
