<?php
/**
 * Created by PhpStorm.
 * User: Danilo
 * Date: 28/07/2018
 * Time: 15:06
 */

namespace Apli\Data;

use OutOfBoundsException;
use JsonSerializable;

/**
 * Class entry represents a key and an associated value.
 *
 * @package Apli\Data
 */
class Entry implements JsonSerializable
{

    /**
     * @var mixed $key The entry key's.
     */
    public $key;

    /**
     * @var mixed $value The entry value.
     */
    public $value;

    /**
     * Pair constructor.
     *
     * @param mixed $key
     * @param mixed $value
     */
    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * This allows unset($entry->key) to not completely remove the property,
     * but be set to null instead.
     *
     * @param mixed $name
     *
     * @return mixed|null
     */
    public function __get($name)
    {
        if ($name === 'key' || $name === 'value') {
            $this->$name = null;
            return;
        }

        throw new OutOfBoundsException();
    }

    /**
     * Returns a copy of the Pair
     *
     * @return Entry
     */
    public function copy()
    {
        return new self($this->key, $this->value);
    }

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        return ['key' => $this->key, 'value' => $this->value];
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return mixed
     */
    public function __toString()
    {
        return $this->getValue();
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }
}
