<?php
/**
 * Created by PhpStorm.
 * User: Danilo
 * Date: 02/08/2018
 * Time: 10:16
 */

namespace Apli\Data;

use Traversable;

/**
 * PriorityQueue is like Queue but with an assigned priority, and the value
 * with the highest priority will always be at the top of the queue.
 *
 * @package Apli\Data
 */
class PriorityQueue implements \IteratorAggregate, Collection
{
    use Traits\GenericCollection;
    use Traits\SquaredCapacity;

    /**
     * @var int
     */
    const MIN_CAPACITY = 8;

    /**
     * @var array
     */
    private $heap = [];

    /**
     * @var int
     */
    private $stamp = 0;

    /**
     * Creates a new instance.
     */
    public function __construct()
    {
    }

    /**
     * Removes all values from the collection.
     */
    function clear()
    {
        // TODO: Implement clear() method.
    }

    /**
     * Returns the size of the collection.
     *
     * @return int
     */
    function count()
    {
        // TODO: Implement count() method.
    }

    /**
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     * @since 5.0.0
     */
    public function getIterator()
    {
        // TODO: Implement getIterator() method.
    }

    /**
     * Returns an array representation of the collection.
     *
     * The format of the returned array is implementation-dependent. Some
     * implementations may throw an exception if an array representation
     * could not be created (for example when object are used as keys).
     *
     * @return array
     */
    public function toArray()
    {
        // TODO: Implement toArray() method.
    }
}
