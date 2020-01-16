# PHP Bitmask implementation derived from `myclabs/php-enum`

[![Build Status](https://travis-ci.org/cruxinator/php-bitmask.svg?branch=master)](https://travis-ci.org/cruxinator/php-bitmask)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/cruxinator/php-bitmask/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/cruxinator/php-bitmask/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/cruxinator/php-bitmask/badge.svg?branch=master)](https://coveralls.io/github/cruxinator/php-bitmask?branch=master)
[![Latest Stable Version](https://poser.pugx.org/cruxinator/php-bitmask/v/stable)](https://packagist.org/packages/cruxinator/php-bitmask)
[![Latest Unstable Version](https://poser.pugx.org/cruxinator/php-bitmask/v/unstable)](https://packagist.org/packages/cruxinator/php-bitmask)
[![Total Downloads](https://poser.pugx.org/cruxinator/php-bitmask/downloads)](https://packagist.org/packages/cruxinator/php-bitmask)
[![Monthly Downloads](https://poser.pugx.org/cruxinator/php-bitmask/d/monthly)](https://packagist.org/packages/cruxinator/php-bitmask)
[![Daily Downloads](https://poser.pugx.org/cruxinator/php-bitmask/d/daily)](https://packagist.org/packages/cruxinator/php-bitmask)

## Why?

First, and mainly, `myclabs/php-enum` is restricted and can't be composited (Enum::FOO() | Enum::BAR() doesn't work too well).

Using a Bitmask instead of an integer provides the following advantages:

- You can type-hint: `function setAction(Bitmask $action) {`;
- You can enrich the Bitmask with methods (e.g. `format`, `parse`, …);
- You can extend the Bitmask to add new values (make your enum `final` to prevent it);
- You can get a list of all the possible values (see below);
- A Bitmask can many enums in a single value.

This Bitmask class is not intended to replace enums, but only to be used when it makes sense.

## Installation

```
composer require cruxinator/php-bitmask
```

## Declaration

```php
use Cruxinator\BitMask\BitMask ;

class UserStatus extends BitMask
{

const Registered = 1; // BIT #1 of $flags has the value 1

const Active = 2; // BIT #2 of $flags has the value 2

const Member = 4; // BIT #3 of $flags has the value 4

const Admin = 8; // BIT #4 of $flags has the value 8

}

```


## Usage

```php
$status = UserStatus::Registered();

// or with a dynamic key:
$status = UserStatus::$key();
// or with a dynamic value:
$status = new UserStatus($value);

// values can then be checked
if ($status->isActive()){
// ...
}

// individuals flags can later toggled ON
$status->setActive(true);
// or off
$status->setActive(false);
```

As you can see, methods are automatically implemented to provide quick access to a Bitmask value.

One advantage over using class constants is to be able to type-hint Bitmap values:

```php
function setStatus(UserStatus $action) {
    // ...
}
```

## Documentation

- `__construct()` The constructor checks that the value can be composed from the enum;
- `__toString()` You can `echo $myValue`, it will display the bitmask value (in an array style format of Boolean)
- `getValue()` Returns the current value of the Bitmask
- `getKey()` Returns a key arrat of the current composite parts of the Bitmask
- `equals()` Tests whether Bitmask instances are equal (returns `true` if enum values are equal, `false` otherwise)

Static methods:

- `toArray()` Returns all possible values as an array (constant name in key, constant value in value)
- `keys()` Returns the names (keys) of all constants in the Enum class
- `values()` Returns instances of the Enum class of all Enum constants (constant name in key, Enum instance in value)
- `isValid()` Check if tested value is valid on enum set
- `isValidKey()` Check if tested key is valid on enum set
- `search()` Return key for searched value

### dynamic methods

```php
class UserStatus extends BitMask
{
    private const Registered = 1;
    private const Active = 2;
}

// Static method:
$status = UserStatus::Registered();
$status = UserStatus::Active();

// instance methods
$status->isRegistered();
$status->isActive();
$status->setRegistered($bool);
$status->setActive($bool);
```

Static method helpers are implemented using [`__callStatic()`](http://www.php.net/manual/en/language.oop5.overloading.php#object.callstatic) and [`__call()`](https://www.php.net/manual/en/language.oop5.overloading.php#object.call).

If you care about IDE autocompletion, you can either implement the static methods yourself:

```php
class UserStatus extends BitMask
{
    private const Registered = 1;

    /**
     * @return UserStatus
     */
    public static function Registered() {
        return new UserStatus(self::Registered);
    }
    /**
     * @return bool
     */
    public function isRegistered(){
        return $this->isFlagSet(self::Registered);
    }
    /**
     * @param bool $flag
     * @return self
     */
    public function setRegistered(bool $flag){
        return $this->setFlag(self::Registered, $flag);
    }
}
```

Or you can use phpdoc (this is supported in PhpStorm for example):

```php
/**
 * @method static UserStatus Registered()
 * @method bool isRegistered()
 * @method UserStatus setRegistered(bool)
 */
class UserStatus extends Bitmask
{
    private const Registered = 1;
}
```

 ## Related projects

<!--- - [Laravel Bitmask Casts](https://github.com/cruxinator/laravel-bitmask-casts) --->
-  [myclabs/php-enum] (https://github.com/myclabs/php-enum)
