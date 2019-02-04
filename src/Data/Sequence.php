<?php
/**
 *  Copyright (c) 2018 Danilo Andrade.
 *
 *  This file is part of the apli project.
 *
 * @project apli
 * @file Sequence.php
 *
 * @author Danilo Andrade <danilo@webbingbrasil.com.br>
 * @date 27/08/18 at 10:27
 */

/**
 * Created by PhpStorm.
 * User: Danilo
 * Date: 29/07/2018
 * Time: 08:20.
 */

namespace Apli\Data;

/**
 * Interface Sequence describes the behaviour of values arranged in a single, linear dimension.
 */
interface Sequence extends Collection
{
    /**
     * Ensures that enough memory is allocated for a required capacity.
     *
     * @param int $capacity The capacity should be allocated.
     *
     * @return mixed
     */
    public function allocate($capacity);

    /**
     * Updates every value in the sequence by applying a callback, using the
     * return value as the new value.
     *
     * @param callable $callback Accepts the value, returns the new value.
     */
    public function apply(callable $callback);

    /**
     * Returns the current capacity of the sequence.
     *
     * @return int
     */
    public function capacity();

    /**
     * Determines whether the sequence contains all of zero or more values.
     *
     * @param mixed ...$values
     *
     * @return bool true if at least one value was provided and the sequence
     *              contains all given values, false otherwise.
     */
    public function contains(...$values);

    /**
     * Returns a new sequence containing only the values for which a callback
     * returns true. A boolean test will be used if a callback is not provided.
     *
     * @param callable|null $callback Accepts a value, returns a boolean result:
     *                                true : include the value,
     *                                false: skip the value.
     *
     * @return Sequence
     */
    public function filter(callable $callback = null);

    /**
     * Returns the first value in the sequence.
     *
     * @throws \UnderflowException if the sequence is empty.
     *
     * @return mixed
     */
    public function first();

    /**
     * Returns the value at a given index (position) in the sequence.
     *
     * @param int $index
     *
     * @throws \OutOfRangeException if the index is not in the range [0, size-1]
     *
     * @return mixed
     */
    public function get($index);

    /**
     * Inserts zero or more values at a given index.
     *
     * Each value after the index will be moved one position to the right.
     * Values may be inserted at an index equal to the size of the sequence.
     *
     * @param int   $index
     * @param mixed ...$values
     *
     * @throws \OutOfRangeException if the index is not in the range [0, n]
     */
    public function insert($index, ...$values);

    /**
     * Joins all values of the sequence into a string, adding an optional 'glue'
     * between them. Returns an empty string if the sequence is empty.
     *
     * @param string $glue
     *
     * @return string
     */
    public function join($glue = null);

    /**
     * Returns the last value in the sequence.
     *
     * @throws \UnderflowException if the sequence is empty.
     *
     * @return mixed
     */
    public function last();

    /**
     * Returns a new sequence using the results of applying a callback to each
     * value.
     *
     * @param callable $callback
     *
     * @return Sequence
     */
    public function map(callable $callback);

    /**
     * Returns the result of adding all given values to the sequence.
     *
     * @param array|\Traversable $values
     *
     * @return Sequence
     */
    public function merge($values);

    /**
     * Removes the last value in the sequence, and returns it.
     *
     * @throws \UnderflowException if the sequence is empty.
     *
     * @return mixed what was the last value in the sequence.
     */
    public function pop();

    /**
     * Adds zero or more values to the end of the sequence.
     *
     * @param mixed ...$values
     */
    public function push(...$values);

    /**
     * Iteratively reduces the sequence to a single value using a callback.
     *
     * @param callable   $callback Accepts the carry and current value, and
     *                             returns an updated carry value.
     * @param mixed|null $initial  Optional initial carry value.
     *
     * @return mixed The carry value of the final iteration, or the initial
     *               value if the sequence was empty.
     */
    public function reduce(callable $callback, $initial = null);

    /**
     * Removes and returns the value at a given index in the sequence.
     *
     * @param int $index this index to remove.
     *
     * @throws \OutOfRangeException if the index is not in the range [0, size-1]
     *
     * @return mixed the removed value.
     */
    public function remove($index);

    /**
     * Reverses the sequence in-place.
     */
    public function reverse();

    /**
     * Returns a reversed copy of the sequence.
     *
     * @return Sequence
     */
    public function reversed();

    /**
     * Rotates the sequence by a given number of rotations, which is equivalent
     * to successive calls to 'shift' and 'push' if the number of rotations is
     * positive, or 'pop' and 'unshift' if negative.
     *
     * @param int $rotations The number of rotations (can be negative).
     */
    public function rotate($rotations);

    /**
     * Removes and returns the first value in the sequence.
     *
     * @throws \UnderflowException if the sequence was empty.
     *
     * @return mixed what was the first value in the sequence.
     */
    public function shift();

    /**
     * Returns a sub-sequence of a given length starting at a specified index.
     *
     * @param int $index  If the index is positive, the sequence will start
     *                    at that index in the sequence. If index is negative,
     *                    the sequence will start that far from the end.
     * @param int $length If a length is given and is positive, the resulting
     *                    sequence will have up to that many values in it.
     *                    If the length results in an overflow, only values
     *                    up to the end of the sequence will be included.
     *
     *                    If a length is given and is negative, the sequence
     *                    will stop that many values from the end.
     *
     *                    If a length is not provided, the resulting sequence
     *                    will contain all values between the index and the
     *                    end of the sequence.
     *
     * @return Sequence
     */
    public function slice($index, $length = null);

    /**
     * Sorts the sequence in-place, based on an optional callable comparator.
     *
     * @param callable|null $comparator Accepts two values to be compared.
     *                                  Should return the result of a <=> b.
     */
    public function sort(callable $comparator = null);

    /**
     * Returns a sorted copy of the sequence, based on an optional callable
     * comparator. Natural ordering will be used if a comparator is not given.
     *
     * @param callable|null $comparator Accepts two values to be compared.
     *                                  Should return the result of a <=> b.
     *
     * @return Sequence
     */
    public function sorted(callable $comparator = null);

    /**
     * Returns the sum of all values in the sequence.
     *
     * @return int|float The sum of all the values in the sequence.
     */
    public function sum();

    /**
     * Adds zero or more values to the front of the sequence.
     *
     * @param mixed ...$values
     */
    public function unshift(...$values);
}
