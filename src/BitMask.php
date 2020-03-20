<?php

declare(strict_types=1);

namespace Cruxinator\BitMask;

use BadMethodCallException;
use MyCLabs\Enum\Enum;

abstract class BitMask extends Enum
{
    protected function isFlag(int $flag): bool
    {
        return $flag === 0 ? $this->value === 0 : (($this->value & $flag) == $flag);
    }

    protected function setFlag(int $flag, bool $value)
    {
        if ($value) {
            $this->value = $flag === 0 ? 0 : $this->value | $flag;
        } else {
            $this->value = $flag === 0 ? $this->value : $this->value & ~$flag;
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
        $array = static::toArray();
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
        $array = parent::toArray();
        //TODO: check that the array is defined correctly. basically check everything in the array is a int.
        /*$array = array_filter($array, function ($temp) {
            $raw = log($temp, 2);
            return is_int($temp) && 0.01 > abs($raw - round($raw));
        }
        );*/
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
