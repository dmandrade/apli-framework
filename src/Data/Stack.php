<?php
/**
 * Created by PhpStorm.
 * User: Danilo
 * Date: 02/08/2018
 * Time: 10:15
 */

namespace Apli\Data;

use Error;
use OutOfBoundsException;

/**
 * Class Stack only allows access to the value at the top
 * of the structure and iterates in that order, destructively.
 * @package Apli\Data
 */
class Stack implements \IteratorAggregate, \ArrayAccess, Collection
{
    use Traits\GenericCollection;

    /**
     * @var Vector internal vector to store values of the stack.
     */
    private $vector;

    /**
     * Creates an instance using the values of an array or Traversable object.
     *
     * @param array|\Traversable $values
     */
    public function __construct($values = null)
    {
        $this->vector = new Vector($values ?: []);
    }

    /**
     * Clear all elements in the Stack
     */
    public function clear()
    {
        $this->vector->clear();
    }

    /**
     * Creates a shallow copy of the object.
     *
     * @return self|Collection
     */
    public function copy()
    {
        return new self($this->vector);
    }

    /**
     * Returns the number of elements in the Stack
     *
     * @return int
     */
    public function count()
    {
        return count($this->vector);
    }

    /**
     * Ensures that enough memory is allocated for a specified capacity. This
     * potentially reduces the number of reallocations as the size increases.
     *
     * @param int $capacity The number of values for which capacity should be
     *                      allocated. Capacity will stay the same if this value
     *                      is less than or equal to the current capacity.
     */
    public function allocate($capacity)
    {
        $this->vector->allocate($capacity);
    }

    /**
     * Returns the current capacity of the stack.
     *
     * @return int
     */
    public function capacity()
    {
        return $this->vector->capacity();
    }

    /**
     * Returns the value at the top of the stack without removing it.
     *
     * @return mixed
     *
     * @throws \UnderflowException if the stack is empty.
     */
    public function peek()
    {
        return $this->vector->last();
    }

    /**
     * Returns and removes the value at the top of the stack.
     *
     * @return mixed
     *
     * @throws \UnderflowException if the stack is empty.
     */
    public function pop()
    {
        return $this->vector->pop();
    }

    /**
     * Pushes zero or more values onto the top of the stack.
     *
     * @param mixed ...$values
     */
    public function push(...$values)
    {
        $this->vector->push(...$values);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array_reverse($this->vector->toArray());
    }

    /**
     * @return \Generator|\Traversable
     */
    public function getIterator()
    {
        while ( ! $this->isEmpty()) {
            yield $this->pop();
        }
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if ($offset === null) {
            $this->push($value);
        } else {
            throw new OutOfBoundsException();
        }
    }

    /**
     * @param mixed $offset
     * @return mixed|void
     */
    public function offsetGet($offset)
    {
        throw new Error();
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        throw new Error();
    }

    /**
     * @param mixed $offset
     * @return bool|void
     */
    public function offsetExists($offset)
    {
        throw new Error();
    }
}
