[![StyleCI](https://github.styleci.io/repos/140430023/shield?branch=master)](https://github.styleci.io/repos/140430023)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/27c04dc7969e4a5b86b74ab3050b1b05)](https://www.codacy.com/app/mandrade.danilo/apli-filter?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=dmandrade/apli-filter&amp;utm_campaign=Badge_Grade)

# Aplí Filter

A simple package to sanitize input/output user data

## Installation via Composer

Add this to the require block in your `composer.json`.

``` json
{
    "require": {
        "apli/filter": "~1.0"
    }
}
```

## How to Use

Create a filter object and use `clean()` to filter input string.

``` php
use Apli\Filter\InputFilter;

$filter = new InputFilter;

$username = $_REQUEST['username'];

$username = $filter->clean($username, 'string');
```


### Add Custom Rules

Using closure as filter rule.

``` php
$closure = function($value)
{
    return str_replace('Bruce Wayne', 'Batman!', $value);
};

$filter->setHandler('armor', $closure);

$string = $filter->clean("I'm Bruce Wayne", 'armor');

// $string will be "I'm Batman!"
```

Using Cleaner object

``` php
use Apli\Filter\Cleaner\Cleaner;

class ArmorCleaner implements Cleaner
{
    public function clean($source)
    {
        return str_replace('Tony Stark', 'Iron Man', $value);
    }
}

$filter->setHandler('armor', new ArmorCleaner);

$string = $filter->clean("Hi I'm Tony Stark~~~", 'armor');

// $string will be "Hi I'm Iron Man"
```
