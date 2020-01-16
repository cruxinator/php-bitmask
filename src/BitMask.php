<?php declare(strict_types=1);

namespace Cruxinator\BitMask;

use function array_key_exists;
use BadMethodCallException;
use MyCLabs\Enum\Enum;

abstract class BitMask extends Enum
{
    protected function isFlagSet(int $flag) : bool
    {
        return (($this->value & $flag) == $flag);
    }

    protected function setFlag(int $flag, bool $value)
    {
        if ($value) {
            $this->value |= $flag;
        } else {
            $this->value &= ~$flag;
        }
    }

    /**
     * @param $name
     * @param $arguments
     * @throws \ReflectionException
     * @return bool|null
     */
    public function __call($name, $arguments)
    {
        $array = static::toArray();

        if (substr($name, 0, 2) === 'is') {
            $actualName = substr($name, 2);
            if (isset($array[$actualName]) || array_key_exists($actualName, $array)) {
                return $this->isFlagSet($array[$actualName]);
            }
        } elseif (substr($name, 0, 3) === 'set') {
            $actualName = substr($name, 3);
            if (isset($array[$actualName]) || array_key_exists($actualName, $array)) {
                return $this->setFlag($array[$actualName], $arguments[0]);
            }
        }
        throw new BadMethodCallException(sprintf('Method %s not found on Class %s', $name, get_class($this)));
    }

    /**
     * @param $value
     * @throws \ReflectionException
     * @return bool
     */
    public static function isValid($value)
    {
        $min = min(static::toArray());
        $max = max(static::toArray()) * 2 - 1;
        return $value >= $min && $value <= $max;
    }

    /**
     * @throws \ReflectionException
     * @return array
     */
    public static function toArray()
    {
        $array = parent::toArray();
        //TODO: check that the array is defined correctly.
        /*$array = array_filter($array, function ($temp) {
            $raw = log($temp, 2);
            return is_int($temp) && 0.01 > abs($raw - round($raw));
        }
        );*/
        return $array;
    }

    /**
     * @throws \ReflectionException
     * @return array|mixed
     */
    public function getKey()
    {
        $value = $this->value;
        $f = array_filter(static::toArray(), function (
            /* @noinspection PhpUnusedParameterInspection needed for function def */ $key) use (&$value) {
            $isSet = $value & 1;
            $value = $value >> 1;
            return $isSet;
        });

        return array_keys($f);
    }

    /**
     * @throws \ReflectionException
     * @return string
     */
    public function __toString()
    {
        $name = $this->getName();
        $array = static::toArray();
        $ret = '';
        foreach ($array as $key => $value) {
            $ret .= "'" . $key . "' => " . ($this->{'is' . $key}() ? 'TRUE' : 'FALSE') . PHP_EOL;
        }
        return $name . '[' . PHP_EOL .
            $ret .
            ']'. PHP_EOL;
    }

    public function getName()
    {
        $path = explode('\\', __CLASS__);
        return array_pop($path);
    }
}
