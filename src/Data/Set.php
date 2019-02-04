<?php
/**
 *  Copyright (c) 2018 Danilo Andrade
 *
 *  This file is part of the apli project.
 *
 * @project apli
 * @file Set.php
 * @author Danilo Andrade <danilo@webbingbrasil.com.br>
 * @date 27/08/18 at 10:27
 */

namespace Apli\Data;

use Error;
use OutOfBoundsException;
use OutOfRangeException;

/**
 * Class Set represent a sequence of unique values.
 * @package Apli\Data
 */
final class Set implements Collection
{
    use Traits\GenericCollection;

    const MIN_CAPACITY = Map::MIN_CAPACITY;

    /**
     * @var Map internal map to store the values.
     */
    private $table;

    /**
     * Creates a new set using the values of an array or Traversable object.
     * The keys of either will not be preserved.
     *
     * @param array|\Traversable|null $values
     */
    public function __construct($values = null)
    {
        $this->table = new Map();

        if (func_num_args()) {
            $this->add(...$values);
        }
    }

    /**
     * Adds zero or more values to the set.
     *
     * @param mixed ...$values
     */
    public function add(...$values)
    {
        foreach ($values as $value) {
            $this->table->put($value, null);
        }
    }

    /**
     * Ensures that enough memory is allocated for a specified capacity. This
     * potentially reduces the number of reallocations as the size increases.
     *
     * @param int $capacity The number of values for which capacity should be allocated.
     */
    public function allocate($capacity)
    {
        $this->table->allocate($capacity);
    }

    /**
     * Returns the current capacity of the set.
     *
     * @return int
     */
    public function capacity()
    {
        return $this->table->capacity();
    }

    /**
     * Clear all elements in the Set
     */
    public function clear()
    {
        $this->table->clear();
    }

    /**
     * Returns the number of elements in the Stack
     *
     * @return int
     */
    public function count()
    {
        return $this->table->count();
    }

    /**
     * Creates a new set using values in either this set or in another set,
     * but not in both.
     *
     * Formally: A ⊖ B = {x : x ∈ (A \ B) ∪ (B \ A)}
     *
     * @param Set $set
     *
     * @return Set
     */
    public function doXor(Set $set)
    {
        return $this->table->doXor($set->table)->keys();
    }

    /**
     * Returns the value at a specified position in the set.
     *
     * @param int $position
     *
     * @return mixed|null
     *
     * @throws OutOfRangeException
     */
    public function get($position)
    {
        return $this->table->skip($position)->key;
    }

    /**
     * Creates a new set using values common to both this set and another set.
     *
     * In other words, returns a copy of this set with all values removed that
     * aren't in the other set.
     *
     * Formally: A ∩ B = {x : x ∈ A ∧ x ∈ B}
     *
     * @param Set $set
     *
     * @return Set
     */
    public function intersect(Set $set)
    {
        return $this->table->intersect($set->table)->keys();
    }

    /**
     * Joins all values of the set into a string, adding an optional 'glue'
     * between them. Returns an empty string if the set is empty.
     *
     * @param string $glue
     *
     * @return string
     */
    public function join($glue = null)
    {
        return implode($glue, $this->toArray());
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return iterator_to_array($this);
    }

    /**
     * Iteratively reduces the set to a single value using a callback.
     *
     * @param callable   $callback Accepts the carry and current value, and
     *                           returns an updated carry value.
     *
     * @param mixed|null $initial Optional initial carry value.
     *
     * @return mixed The carry value of the final iteration, or the initial
     *               value if the set was empty.
     */
    public function reduce(callable $callback, $initial = null)
    {
        $carry = $initial;

        foreach ($this as $value) {
            $carry = $callback($carry, $value);
        }

        return $carry;
    }

    /**
     * Removes zero or more values from the set.
     *
     * @param mixed ...$values
     */
    public function remove(...$values)
    {
        foreach ($values as $value) {
            $this->table->remove($value, null);
        }
    }

    /**
     * Reverses the set in-place.
     */
    public function reverse()
    {
        $this->table->reverse();
    }

    /**
     * Returns a reversed copy of the set.
     *
     * @return Set
     */
    public function reversed()
    {
        $reversed = $this->copy();
        $reversed->table->reverse();

        return $reversed;
    }

    /**
     * Returns a subset of a given length starting at a specified offset.
     *
     * @param int $offset If the offset is non-negative, the set will start
     *                    at that offset in the set. If offset is negative,
     *                    the set will start that far from the end.
     *
     * @param int $length If a length is given and is positive, the resulting
     *                    set will have up to that many values in it.
     *                    If the requested length results in an overflow, only
     *                    values up to the end of the set will be included.
     *
     *                    If a length is given and is negative, the set
     *                    will stop that many values from the end.
     *
     *                    If a length is not provided, the resulting set
     *                    will contains all values between the offset and the
     *                    end of the set.
     *
     * @return Set
     */
    public function slice($offset, $length = null)
    {
        $sliced = new self();
        $sliced->table = $this->table->slice($offset, $length);

        return $sliced;
    }

    /**
     * Sorts the set in-place, based on an optional callable comparator.
     *
     * @param callable|null $comparator Accepts two values to be compared.
     *                                  Should return the result of a <=> b.
     */
    public function sort(callable $comparator = null)
    {
        $this->table->ksort($comparator);
    }

    /**
     * Returns a sorted copy of the set, based on an optional callable
     * comparator. Natural ordering will be used if a comparator is not given.
     *
     * @param callable|null $comparator Accepts two values to be compared.
     *                                  Should return the result of a <=> b.
     *
     * @return Set
     */
    public function sorted(callable $comparator = null)
    {
        $sorted = $this->copy();
        $sorted->table->ksort($comparator);

        return $sorted;
    }

    /**
     * Returns the result of adding all given values to the set.
     *
     * @param array|\Traversable $values
     *
     * @return Set
     */
    public function merge($values)
    {
        $merged = $this->copy();

        foreach ($values as $value) {
            $merged->add($value);
        }

        return $merged;
    }

    /**
     * Returns the sum of all values in the set.
     *
     * @return int|float The sum of all the values in the set.
     */
    public function sum()
    {
        return array_sum($this->toArray());
    }

    /**
     * Creates a new set that contains the values of this set as well as the
     * values of another set.
     *
     * Formally: A ∪ B = {x: x ∈ A ∨ x ∈ B}
     *
     * @param Set $set
     *
     * @return Set
     */
    public function union(Set $set)
    {
        $union = new self();

        foreach ($this as $value) {
            $union->add($value);
        }

        foreach ($set as $value) {
            $union->add($value);
        }

        return $union;
    }

    /**
     * Get iterator
     */
    public function getIterator()
    {
        foreach ($this->table as $key => $value) {
            yield $key;
        }
    }

    /**
     * @inheritdoc
     *
     * @throws OutOfBoundsException
     */
    public function offsetSet($offset, $value)
    {
        if ($offset === null) {
            $this->add($value);
            return;
        }

        throw new OutOfBoundsException();
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->table->skip($offset)->key;
    }

    /**
     * @param mixed $offset
     * @return bool|void
     *
     * @throws Error
     */
    public function offsetExists($offset)
    {
        throw new Error();
    }

    /**
     * @param mixed $offset
     *
     * @throws Error
     */
    public function offsetUnset($offset)
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
    function set($key, $value)
    {
        $this->table->put($value, null);
    }

    /**
     * @param $items
     *
     * @throws Error
     */
    public function diffKeys($items)
    {
        throw new Error();
    }
}
