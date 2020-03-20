<?php

declare(strict_types=1);

namespace Cruxinator\BitMask;

use BadMethodCallException;
use UnexpectedValueException;
use MyCLabs\Enum\Enum;

abstract class BitMask extends Enum
{
    // Because 0 is the identity element when ORing a bitmask, we have to special-case it when ANDing bitmasks
    // (akin to ordinary division by 0, the addition identity element on integers/rationals/reals)

    protected function isFlag(int $flag): bool
    {
        return 0 === $flag ? 0 === $this->value : (($this->value & $flag) == $flag);
    }

    protected function setFlag(int $flag, bool $value)
    {
        if ($value) {
            $this->value = 0 === $flag ? 0 : $this->value | $flag;
        } else {
            $this->value = 0 === $flag  ? $this->value : $this->value & ~$flag;
        }
        return $this;
    }

    /**
     * @param $name
     * @param $arguments
     * @return bool|self
     */
    public function __call($name, $arguments)
    {
        $array     = static::toArray();
        $regexBase = '/(is|set)(%s)/m';
        $regexFull = sprintf($regexBase, implode('|', array_keys($array)));
        preg_match($regexFull, $name, $match);
        if (count($match)>0 && $match[0] === $name) {
            return $this->{$match[1] . 'Flag'}($array[$match[2]], $arguments[0] ?? true);
        }
        throw new BadMethodCallException(sprintf('Enum %s not found on %s', $name, get_class($this)));
    }

    /**
     * @param $value
     * @return bool
     */
    public static function isValid($value)
    {
        $min = min(static::toArray());
        $max = max(static::toArray()) * 2 - 1;
        return $value >= $min && $value <= $max;
    }

    /**
     * @return array
     */
    public static function toArray()
    {
        if (!isset(static::$cache[static::class])) {
            $array = parent::toArray();
            array_walk($array, function ($item) {
                if (!is_integer($item)) {
                    throw new UnexpectedValueException(sprintf('All defined Const on Enum %s should be integers', static::class));
                }
            });
        }
        $array = parent::toArray();
        return $array;
    }

    /**
     * @return array|mixed
     */
    public function getKey()
    {
        $value = $this->value;
        $f     = array_filter(static::toArray(), function () use (&$value) {
            $isSet = $value & 1;
            $value = $value >> 1;
            return $isSet;
        });

        return array_keys($f);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $name  = $this->getName();
        $array = static::toArray();
        $ret   = '';
        foreach ($array as $key => $value) {
            $ret .= "'" . $key . "' => " . ($this->{'is' . $key}() ? 'TRUE' : 'FALSE') . PHP_EOL;
        }
        return $name . '[' . PHP_EOL .
            $ret .
            ']' . PHP_EOL;
    }

    public function getName()
    {
        $path = explode('\\', __CLASS__);
        return array_pop($path);
    }
}
