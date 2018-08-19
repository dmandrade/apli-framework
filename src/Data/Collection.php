<?php
/**
 * Created by PhpStorm.
 * User: Danilo
 * Date: 28/07/2018
 * Time: 14:55
 */

namespace Apli\Data;

use Traversable;
use IteratorAggregate;
use ArrayAccess;
use JsonSerializable;
use Countable;
use Apli\Support\Arrayable;
use Apli\Support\Jsonable;

interface Collection extends Traversable, IteratorAggregate, ArrayAccess, JsonSerializable, Countable, Arrayable, Jsonable
{
    /**
     * Get all of the items in the collection.
     *
     * @return mixed
     */
    public function all();

    /**
     * Replaces the value at a given key in the collection with a new value.
     *
     * @param int   $key
     * @param mixed $value
     *
     * @throws \OutOfRangeException if the index is not in the range [0, size-1]
     */
    function set($key, $value);

    /**
     * Execute a callback over each item.
     *
     * @param  callable  $callback
     * @return $this
     */
    function each(callable $callback);

    /**
     * Returns the key of a given value, or false if it could not be found.
     *
     * @param mixed $value
     *
     * @return false|int|string
     */
    function searchStrict($value);

    /**
     * Returns the key of a given value, or false if it could not be found.
     *
     * @param mixed $value
     *
     * @return false|int|string
     */
    public function search($value);

    /**
     * Removes all values from the collection.
     */
    function clear();

    /**
     * Returns a shallow copy of the collection.
     *
     * @return Collection a copy of the collection.
     */
    function copy();

    /**
     * Returns whether the collection is empty.
     *
     * @return bool
     */
    function isEmpty();

    /**
     * Returns whether the collection is  notempty.
     *
     * @return bool whether the collection is not empty.
     */
    public function isNotEmpty();

}
