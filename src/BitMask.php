<?php

declare(strict_types=1);

namespace Cruxinator\BitMask;

use BadMethodCallException;
use MyCLabs\Enum\Enum;
use ReflectionException;
use UnexpectedValueException;

abstract class BitMask extends Enum
{
    // Because 0 is the identity element when ORing a bitmask, we have to special-case it when ANDing bitmasks
    // (akin to ordinary division by 0, the addition identity element on integers/rationals/reals)

    protected static function assertValidValueReturningKey($value): string
    {
        if (!static::isValid($value)) {
            throw new UnexpectedValueException("Value '$value' is not part of the enum ".static::class);
        }

        return implode('|', self::getKeyArray($value));
    }

    /**
     * @param $value
     *
     * @return bool
     */
    public static function isValid($value): bool
    {
        $min = min(static::toArray());
        $max = max(static::toArray()) * 2 - 1;

        return $value >= $min && $value <= $max;
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @throws ReflectionException
     *
     * @return bool|self
     */
    public function __call($name, $arguments)
    {
        $array = static::toArray();
        $regexBase = '/(isComponentOf|is|set)(%s)/m';
        $regexFull = sprintf($regexBase, implode('$|', array_keys($array)));
        preg_match($regexFull, $name, $match);
        if (count($match) > 0 && $match[0] === $name) {
            return $this->{$match[1].'Flag'}($array[$match[2]], $arguments[0] ?? true);
        }

        throw new BadMethodCallException(sprintf('Enum %s not found on %s', $name, get_class($this)));
    }

    /**
     * @return array
     */
    public static function toArray(): array
    {
        $firstTime = !isset(static::$cache[static::class]);
        $array = array_filter(parent::toArray(), static function ($value): bool {
            return is_scalar($value);
        });
        $firstTime && array_walk($array, static function ($item): void {
            if (!is_int($item)) {
                throw new UnexpectedValueException(
                    sprintf('All defined Const on Enum %s should be integers', static::class)
                );
            }
        });

        return $array;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return implode('|', self::getKeyArray($this->value));
    }

    /**
     * @param mixed $value
     *
     * @return array
     */
    public static function getKeyArray(mixed $value): array
    {
        $f = array_filter(static::toArray(), static function ($key) use (&$value) {
            return $value & $key;
        });

        return array_keys($f);
    }

    /**
     * @throws ReflectionException
     *
     * @return string
     */
    public function __toString(): string
    {
        $name = $this->getName();
        $array = static::toArray();
        $ret = '';
        foreach ($array as $key => $value) {
            $ret .= "'".$key."' => ".($this->{'is'.$key}() ? 'TRUE' : 'FALSE').PHP_EOL;
        }

        return $name.'['.PHP_EOL.
            $ret.
            ']'.PHP_EOL;
    }

    public function getName(): string
    {
        $path = explode('\\', __CLASS__);

        return array_pop($path);
    }

    protected function isFlag(int $flag): bool
    {
        return 0 === $flag ? 0 === $this->value : (($this->value & $flag) === $flag);
    }

    protected function isComponentOfFlag(int $flag): bool
    {
        return 0 === $flag ? $flag === $this->value : (($this->value & $flag) === $this->value);
    }

    protected function setFlag(int $flag, bool $value): static
    {
        if ($value) {
            $this->value = 0 === $flag ? 0 : $this->value | $flag;
        } else {
            $this->value = 0 === $flag ? $this->value : $this->value & ~$flag;
        }

        return $this;
    }
}
