<?php
/**
 * Created by PhpStorm.
 * User: Danilo
 * Date: 28/07/2018
 * Time: 14:55
 */

namespace Apli\Data;


interface Collection extends \Traversable, \Countable, \JsonSerializable
{
    /**
     * Removes all values from the collection.
     */
    function clear();

    /**
     * Returns the size of the collection.
     *
     * @return int
     */
    function count();

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
     * Returns an array representation of the collection.
     *
     * The format of the returned array is implementation-dependent.
     * Some implementations may throw an exception if an array representation
     * could not be created.
     *
     * @return array
     */
    function toArray();
}
