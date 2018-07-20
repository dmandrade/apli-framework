<?php
/**
 * Created by PhpStorm.
 * User: Danilo
 * Date: 09/07/2018
 * Time: 15:30
 */

namespace Apli\Data;

class Data implements DataInterface, \JsonSerializable, \ArrayAccess, \IteratorAggregate, \Countable
{
    /**
     * Property separator.
     *
     * @var  string
     */
    protected $separator = '.';

    /**
     * Data store.
     *
     * @var    array
     * @since  2.0
     */
    protected $data = [];

    /**
     * Property ignoreValues.
     *
     * @var  array
     */
    protected $ignoreValues = [null];

    /**
     * Constructor.
     *
     * @param mixed $data
     */
    public function __construct($data = null)
    {
        if (null !== $data) {
            $this->bind($data);
        }
    }

    /**
     * Bind the data
     *
     * @param $values
     * @param bool $replaceNulls
     * @return mixed
     */
    public function bind($values, $replaceNulls = false)
    {
        $this->bindData($this->data, $values, $replaceNulls);

        return $this;
    }

    /**
     * Method to recursively bind data to a parent object.
     *
     * @param $parent
     * @param $data
     * @param bool $replaceNulls
     */
    protected function bindData(&$parent, $data, $replaceNulls = false)
    {
        // Ensure the input data is an array.
        $data = Helper::toArray($data, true);

        foreach ($data as $key => $value) {
            if (in_array($value, $this->ignoreValues, true)) {
                continue;
            }

            if (is_array($value)) {
                if (!isset($parent[$key]) || !is_array($parent[$key])) {
                    $parent[$key] = [];
                }

                $this->bindData($parent[$key], $value, $replaceNulls);
                continue;
            }

            if ($value === null && !$replaceNulls) {
                continue;
            }

            $parent[$key] = $value;
        }
    }

    /**
     * Gets this object represented as an RecursiveArrayIterator.
     *
     * This allows the data properties to be accessed via a foreach statement.
     *
     * @return  \RecursiveArrayIterator a object iterator
     */
    public function getIterator()
    {
        return new \RecursiveArrayIterator($this->data);
    }

    /**
     * Checks whether an offset exists in the iterator.
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->exists($offset);
    }

    /**
     * Check if a data path exists.
     *
     * @param $path
     * @return boolean
     */
    public function exists($path)
    {
        return !is_null($this->get($path));
    }

    /**
     * Get a value from data
     *
     * @param $path
     * @param null $default
     * @return null
     */
    public function get($path, $default = null)
    {
        $result = Helper::getByPath($this->data, $path, $this->separator);

        return !is_null($result) ? $result : $default;
    }

    /**
     * Gets an offset in the iterator.
     *
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * Sets an offset in the iterator.
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->set($offset, $value);
    }

    /**
     * Set a value and convert object to array.
     *
     * @param $field
     * @param $value
     * @return $this
     */
    public function set($field, $value)
    {
        if (is_array($value) || is_object($value)) {
            $value = Helper::toArray($value, true);
        }

        Helper::setByPath($this->data, $field, $value, $this->separator);

        return $this;
    }

    /**
     * Unsets an offset in the iterator.
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    /**
     * remove a value from data
     *
     * @param   string $path
     * @return  static
     */
    public function remove($path)
    {
        Helper::removeByPath($this->data, $path, $this->separator);

        return $this;
    }

    /**
     * Count itens in data object
     *
     * @return int
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * Implementation for the JsonSerializable interface.
     * Allows us to pass Structure objects to json_encode.
     *
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return $this->data;
    }

    /**
     * Check if data is not null
     *
     * @return boolean
     */
    public function notNull()
    {
        return !$this->isNull();
    }

    /**
     * Check if data is empty
     *
     * @return boolean
     */
    public function isNull()
    {
        return empty($this->data);
    }

    /**
     * Check a value is set
     *
     * @param   string $field
     *
     * @return  boolean
     */
    public function __isset($field)
    {
        $this->exists($field);
    }

    /**
     * Get a value
     *
     * @param   string $name
     *
     * @return  mixed
     */
    public function __get($name)
    {
        $this->get($name);
    }

    /**
     * Set value.
     *
     * @param string $field The field to set.
     * @param mixed $value The value to set.
     *
     * @return  void
     * @throws \InvalidArgumentException
     */
    public function __set($field, $value = null)
    {
        $this->set($field, $value);
    }

    /**
     * Unset a value
     *
     * @param   string $name
     *
     * @return  void
     */
    public function __unset($name)
    {
        $this->remove($name);
    }

    /**
     * Push value to a path in data
     *
     * @param $path
     * @param $value
     * @return mixed
     */
    public function push($path, $value)
    {
        // TODO: Implement push() method.
    }

    /**
     * Prepend value to a path in data.
     *
     * @param $path
     * @param $value
     * @return mixed
     */
    public function unshift($path, $value)
    {
        // TODO: Implement unshift() method.
    }

    /**
     * To remove first element from the path of this data.
     *
     * @param $path
     * @return mixed
     */
    public function shift($path)
    {
        // TODO: Implement shift() method.
    }

    /**
     * To remove last element from the path of this data.
     *
     * @param $path
     * @return mixed
     */
    public function pop($path)
    {
        // TODO: Implement pop() method.
    }

    /**
     * Reset all data.
     *
     * @return  static
     */
    public function reset()
    {
        // TODO: Implement reset() method.
    }

    /**
     * Merge a data into this object.
     *
     * @param $source
     * @param bool $raw
     * @return mixed
     */
    public function merge($source, $raw = false)
    {
        // TODO: Implement merge() method.
    }

    /**
     * Transforms data to an array
     *
     * @return mixed
     */
    public function toArray()
    {
        // TODO: Implement toArray() method.
    }

    /**
     * Transforms data to an object
     *
     * @param string $class
     * @return mixed
     */
    public function toObject($class = 'stdClass')
    {
        // TODO: Implement toObject() method.
    }

    /**
     * Get data in a given string format
     *
     * @param $format
     * @param array $options
     * @return mixed
     */
    public function toString($format, $options = [])
    {
        // TODO: Implement toString() method.
    }
}