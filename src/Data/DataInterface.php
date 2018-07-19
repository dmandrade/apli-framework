<?php

namespace Apli\Data;

/**
 * Interface DataInterface
 * @package Apli\Data
 */
interface DataInterface
{

    /**
     * Get a value from data
     *
     * @param $path
     * @param null $default
     * @return null
     */
    public function get($path, $default = null);

    /**
     * Set a value and convert object to array.
     *
     * @param $field
     * @param $value
     * @return $this
     */
    public function set($field, $value);

    /**
     * Push value to a path in data
     *
     * @param $path
     * @param $value
     * @return mixed
     */
    public function push($path, $value);

    /**
     * Prepend value to a path in data.
     *
     * @param $path
     * @param $value
     * @return mixed
     */
    public function unshift($path, $value);

    /**
     * To remove first element from the path of this data.
     *
     * @param $path
     * @return mixed
     */
    public function shift($path);


    /**
     * To remove last element from the path of this data.
     *
     * @param $path
     * @return mixed
     */
    public function pop($path);

    /**
     * remove a value from data
     *
     * @param   string $path
     * @return  static
     */
    public function remove($path);

    /**
     * Reset all data.
     *
     * @return  static
     */
    public function reset();

    /**
     * Merge a data into this object.
     *
     * @param $source
     * @param bool $raw
     * @return mixed
     */
    public function merge($source, $raw = false);

    /**
     * Check if a data path exists.
     *
     * @param $path
     * @return boolean
     */
    public function exists($path);

    /**
     * Bind the data
     *
     * @param $values
     * @param bool $replaceNulls
     * @return mixed
     */
    public function bind($values, $replaceNulls = false);

    /**
     * Check if data is empty
     *
     * @return boolean
     */
    public function isNull();

    /**
     * Check if data is not null
     *
     * @return boolean
     */
    public function notNull();

    /**
     * Set value.
     *
     * @param string $field The field to set.
     * @param mixed  $value The value to set.
     *
     * @return  void
     * @throws \InvalidArgumentException
     */
    public function __set($field, $value = null);

    /**
     * Check a value is set
     *
     * @param   string $field
     *
     * @return  boolean
     */
    public function __isset($field);

    /**
     * Get a value
     *
     * @param   string $name
     *
     * @return  mixed
     */
    public function __get($name);

    /**
     * Unset a value
     *
     * @param   string $name
     *
     * @return  void
     */
    public function __unset($name);

    /**
     * Transforms data to an array
     *
     * @return mixed
     */
    public function toArray();

    /**
     * Transforms data to an object
     *
     * @param string $class
     * @return mixed
     */
    public function toObject($class = 'stdClass');

    /**
     * Get data in a given string format
     *
     * @param $format
     * @param array $options
     * @return mixed
     */
    public function toString($format, $options = []);
}
