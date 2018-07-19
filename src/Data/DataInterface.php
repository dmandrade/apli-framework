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
     * @return mixed
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
     * @return mixed
     */
    public function isNull();

    /**
     * Check if data is not null
     *
     * @return mixed
     */
    public function notNull();

    /**
     * Dump all data as array
     *
     * @return  array
     */
    public function dump();

    /**
     * __get
     *
     * @param   string $name
     *
     * @return  mixed
     */
    public function __get($name);

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
