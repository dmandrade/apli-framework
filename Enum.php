<?php
/**
 *  Copyright (c) 2018 Danilo Andrade (http://daniloandrade.net)
 *
 *  This file is part of the Aplí Framework.
 *
 * @project Aplí Framework
 * @file Environment.php
 * @author Danilo Andrade <danilo@daniloandrade.net>
 * @date 07/07/18 at 17:10
 * @copyright  Copyright (c) 2018 Danilo Andrade
 * @license    GNU Lesser General Public License version 3 or later.
 */

namespace Apli\Support;
use ReflectionException;

/**
 * Enum implementation inspired from SplEnum
 *
 * @package Apli\Support
 */
abstract class Enum implements \JsonSerializable
{

    /**
     * Enum value
     *
     * @var mixed
     */
    protected $value;

    /**
     * Store default values in a cache per object.
     *
     * @var array
     */
    private static $defaults;

    /**
     * Store existing constants in a static cache per object.
     *
     * @var array
     */
    protected static $cache = array();

    /**
     * Set name of default constant key
     * @var string
     */
    protected static $defaultKey = "__default";

    /**
     * Creates a new value of some type
     *
     * @param mixed $value
     *
     * @throws \UnexpectedValueException if incompatible type is given.
     * @throws ReflectionException
     */
    public function __construct($value = null)
    {
        if (is_null($value)) {
            $value = self::getDefault();
        }

        if (!$this->isValid($value)) {
            throw new \UnexpectedValueException("Value '$value' is not part of the enum " . get_called_class());
        }
        $this->value = $value;
    }

    /**
     * Get enum value
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Returns the enum key.
     *
     * @return mixed
     * @throws ReflectionException
     */
    public function getKey()
    {
        return static::search($this->value);
    }

    /**
     * Return enum value as string
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->value;
    }

    /**
     * Compares one Enum with another.
     *
     * @param Enum|null $enum
     * @return bool
     */
    final public function equals(Enum $enum = null)
    {
        return $enum !== null && $this->getValue() === $enum->getValue() && get_called_class() == get_class($enum);
    }

    /**
     * Returns the names (keys) of all constants in the Enum class
     *
     * @return array
     * @throws ReflectionException
     */
    public static function keys()
    {
        return array_keys(static::toArray());
    }

    /**
     * Returns instances of the Enum class of all Enum constants
     *
     * @return array
     * @throws ReflectionException
     */
    public static function values()
    {
        $values = array();
        foreach (static::toArray() as $key => $value) {
            $values[$key] = new static($value);
        }
        return $values;
    }

    /**
     * Get default value for enum
     *
     * @return mixed
     */
    public static function getDefault()
    {
        $class = get_called_class();

        if (!isset(static::$defaults[$class])) {
            throw new \UnexpectedValueException("No default enum set");
        }

        return static::$defaults[$class];
    }

    /**
     * Returns all possible values as an array, except default constant
     *
     * @return mixed
     * @throws ReflectionException
     */
    public static function toArray()
    {
        $class = get_called_class();
        if (!array_key_exists($class, static::$cache)) {
            $reflection = new \ReflectionClass($class);
            $constants = $reflection->getConstants();

            if (\array_key_exists(self::$defaultKey, $constants)) {
                static::$defaults[$class] = $constants[self::$defaultKey];
                unset($constants[self::$defaultKey]);
            }

            static::$cache[$class] = $constants;
        }

        return static::$cache[$class];
    }

    /**
     * Check if is valid enum value
     *
     * @param $value
     * @return bool
     * @throws ReflectionException
     */
    public static function isValid($value)
    {
        return in_array($value, static::toArray(), true);
    }

    /**
     * Check if enum key exists
     *
     * @param $key
     * @return bool
     * @throws ReflectionException
     */
    public static function exists($key)
    {
        $array = static::toArray();
        return isset($array[$key]);
    }

    /**
     * Return key for value
     *
     * @param $value
     * @return false|int|string
     * @throws ReflectionException
     */
    public static function search($value)
    {
        return array_search($value, static::toArray(), true);
    }

    /**
     * Returns a value when called statically like so: MyEnum::SOME_VALUE() given SOME_VALUE is a class constant
     *
     * @param $name
     * @param $arguments
     * @return Enum
     * @throws ReflectionException
     * @throws \BadMethodCallException if enum does not exist
     */
    public static function __callStatic($name, $arguments)
    {
        $array = static::toArray();
        if (isset($array[$name])) {
            return new static($array[$name]);
        }
        throw new \BadMethodCallException("No static method or enum constant '$name' in class " . get_called_class());
    }

    /**
     * Specify data which should be serialized to JSON. This method returns data that can be serialized by json_encode()
     * natively.
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        return $this->getValue();
    }
}
