<?php
/**
 *  Copyright (c) 2018 Danilo Andrade.
 *
 *  This file is part of the apli project.
 *
 * @project apli
 * @file Queue.php
 *
 * @author Danilo Andrade <danilo@webbingbrasil.com.br>
 * @date 27/08/18 at 10:27
 */

/**
 * Created by PhpStorm.
 * User: Danilo
 * Date: 02/08/2018
 * Time: 10:15.
 */

namespace Apli\Data;

use Traversable;

/**
 * Queue is a collection that only allows access to the top value
 * of the queue and iterates in that order, destructively.
 */
class Queue implements Collection
{
    use Traits\GenericCollection;

    const MIN_CAPACITY = 8;

    /**
     * @var Deque internal deque to store values.
     */
    private $deque;

    /**
     * Creates an instance using the values of an array or Traversable object.
     *
     * @param array|\Traversable $values
     */
    public function __construct($values = null)
    {
        $this->deque = new Deque($values ?: []);
    }

    /**
     * Removes all values from the collection.
     */
    public function clear()
    {
        $this->deque->clear();
    }

    /**
     * Returns the size of the collection.
     *
     * @return int
     */
    public function count()
    {
        return count($this->deque);
    }

    /**
     * Ensures that enough memory is allocated for a specified capacity. This
     * potentially reduces the number of reallocations as the size increases.
     *
     * @param int $capacity The capacity allocated.
     */
    public function allocate(int $capacity)
    {
        $this->deque->allocate($capacity);
    }

    /**
     * Returns the current capacity of the queue.
     *
     * @return int
     */
    public function capacity()
    {
        return $this->deque->capacity();
    }

    /**
     * Copy queue to a new object.
     *
     * @return Queue
     */
    public function copy()
    {
        return new self($this->deque);
    }

    /**
     * Returns the value at the top of the queue without removing it.
     *
     * @return mixed
     */
    public function peek()
    {
        return $this->deque->first();
    }

    /**
     * Return array of Queue values.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->deque->toArray();
    }

    /**
     * Get iterator.
     *
     * @return \Generator|Traversable
     */
    public function getIterator()
    {
        while (!$this->isEmpty()) {
            yield $this->pop();
        }
    }

    /**
     * Returns and removes the value at the top of the Queue.
     *
     * @return mixed
     */
    public function pop()
    {
        return $this->deque->shift();
    }

    /**
     * Pushe value into the top of queue.
     *
     * @param mixed $offset
     * @param mixed $value
     *
     * @throws OutOfBoundsException
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
     * Pushes values into the top of the queue.
     *
     * @param mixed ...$values
     */
    public function push(...$values)
    {
        $this->deque->push(...$values);
    }

    /**
     * @throws Error
     */
    public function offsetGet($offset)
    {
        throw new Error();
    }

    /**
     * @throws Error
     */
    public function offsetUnset($offset)
    {
        throw new Error();
    }

    /**
     * @throws Error
     */
    public function offsetExists($offset)
    {
        throw new Error();
    }

    /**
     * Replaces the value at a given key in the collection with a new value.
     *
     * @param int   $key
     * @param mixed $value
     *
     * @throws \OutOfRangeException if the index is not in the range [0, size-1]
     */
    public function set($key, $value)
    {
        // TODO: Implement set() method.
    }
}
