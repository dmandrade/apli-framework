<?php
/**
 *  Copyright (c) 2018 Danilo Andrade.
 *
 *  This file is part of the apli project.
 *
 * @project apli
 * @file Data.php
 *
 * @author Danilo Andrade <danilo@webbingbrasil.com.br>
 * @date 27/08/18 at 10:27
 */

/**
 * Created by PhpStorm.
 * User: Danilo
 * Date: 17/08/2018
 * Time: 09:06.
 */

namespace Apli\Data;

use Apli\Data\Traits\GenericCollection;

/**
 * Data is a container to store properties values.
 */
class Data implements Collection
{
    use GenericCollection;

    /**
     * All of the attributes set on the container.
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Create a new data container instance.
     *
     * @param array|object $attributes
     *
     * @return void
     */
    public function __construct($attributes = [])
    {
        if (null !== $attributes) {
            $this->fill($attributes);
        }
    }

    /**
     * Fill data container with values.
     *
     * @param mixed $values       The data array or object.
     * @param bool  $replaceNulls Replace null or not.
     *
     * @throws \InvalidArgumentException
     *
     * @return static Return self to support chaining.
     */
    public function fill($values, $replaceNulls = false)
    {
        if ($values === null) {
            return $this;
        }

        if (!is_array($values) && !is_object($values)) {
            throw new \InvalidArgumentException(sprintf('Please bind array or object, %s given.', gettype($values)));
        }

        if ($values instanceof \Traversable) {
            $values = iterator_to_array($values);
        } elseif (is_object($values)) {
            $values = get_object_vars($values);
        }

        // Bind the properties.
        foreach ($values as $field => $value) {
            if ($value === null && !$replaceNulls) {
                continue;
            }

            $this->attributes[$field] = $value;
        }

        return $this;
    }

    /**
     * Get all attributes.
     *
     * @return array
     */
    public function all()
    {
        return $this->attributes;
    }

    /**
     * Count properties in data container.
     *
     * @return int
     */
    public function count()
    {
        return count($this->attributes);
    }

    /**
     * Determine if the given offset exists.
     *
     * @param string $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * Check if key exists in container.
     *
     * @param $key
     *
     * @return bool
     */
    public function has($key)
    {
        return isset($this->attributes[$key]);
    }

    /**
     * Get the value for a given offset.
     *
     * @param string $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->{$offset};
    }

    /**
     * Set the value at the given offset.
     *
     * @param string $offset
     * @param mixed  $value
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->{$offset} = $value;
    }

    /**
     * Unset the value at the given offset.
     *
     * @param string $offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->{$offset});
    }

    /**
     * Dynamically retrieve the value of an attribute.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Dynamically set the value of an attribute.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return void
     */
    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * Get an attribute from the container.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (array_key_exists($key, $this->attributes)) {
            return $this->attributes[$key];
        }

        return $default;
    }

    /**
     * Set a new property value.
     *
     * @param      $key
     * @param null $value
     *
     * @throws \InvalidArgumentException
     *
     * @return static Return self to support chaining.
     */
    public function set($key, $value = null)
    {
        if ($key === null) {
            throw new \InvalidArgumentException('Cannot access empty property');
        }

        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * Dynamically check if an attribute is set.
     *
     * @param string $key
     *
     * @return bool
     */
    public function __isset($key)
    {
        return $this->has($key);
    }

    /**
     * Dynamically unset an attribute.
     *
     * @param string $key
     *
     * @return void
     */
    public function __unset($key)
    {
        unset($this->attributes[$key]);
    }

    /**
     * Apply a user supplied function to every member of this object.
     *
     * @param callable $callback Callback to handle every element.
     * @param mixed    $userdata This will be passed as the third parameter to the callback.
     *
     * @return static Support chaining.
     */
    public function walk($callback, $userdata = null)
    {
        foreach ($this->getIterator() as $key => $value) {
            call_user_func_array($callback, [&$value, $key, $userdata]);

            $this[$key] = $value;
        }

        return $this;
    }

    /**
     * Retrieve a external iterator.
     *
     * @return \Generator|\Traversable
     */
    public function getIterator()
    {
        foreach ($this->attributes as $key => $value) {
            yield $key => $value;
        }
    }

    /**
     * Get all data properties names.
     *
     * @return array
     */
    public function keys()
    {
        return array_keys($this->toArray());
    }

    /**
     * Convert the data instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->attributes;
    }

    /**
     * Clone this object.
     *
     * @return void
     */
    public function __clone()
    {
        foreach ($this as $key => $item) {
            if (is_object($item)) {
                $this->$key = clone $item;
            }
        }
    }

    /**
     * Removes all values from the collection.
     */
    public function clear()
    {
        $this->attributes = [];
    }
}
