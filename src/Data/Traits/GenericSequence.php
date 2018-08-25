<?php
namespace Apli\Data\Traits;

use Apli\Data\Sequence;
use OutOfRangeException;
use UnderflowException;

/**
 * Common functionality of all structures that implement 'Sequence'. Because the
 * polyfill's only goal is to achieve consistent behaviour, all sequences will
 * share the same implementation using an array array.
 *
 * @package Ds\Traits
 */
trait GenericSequence
{
    /**
     * @var array internal array used to store the values of the sequence.
     */
    private $values = [];

    /**
     * GenericSequence constructor.
     * @param null $values
     */
    public function __construct($values = null)
    {
        if ($values) {
            $this->pushAll($values);
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->values;
    }

    /**
     * @param callable $callback
     */
    public function apply(callable $callback)
    {
        foreach ($this->values as &$value) {
            $value = $callback($value);
        }
    }

    /**
     * @param $values
     * @return Sequence
     */
    public function merge($values)
    {
        $copy = $this->copy();
        $copy->pushAll($values);
        return $copy;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->values);
    }

    /**
     * @param int $index
     * @return mixed
     */
    public function get($index)
    {
        if ( ! $this->validIndex($index)) {
            throw new OutOfRangeException();
        }

        return $this->values[$index];
    }

    /**
     * @param int   $index
     * @param mixed ...$values
     */
    public function insert($index, ...$values)
    {
        if ( ! $this->validIndex($index) && $index !== count($this)) {
            throw new OutOfRangeException();
        }

        array_splice($this->values, $index, 0, $values);
    }

    /**
     * @param string|null $glue
     * @return string
     */
    public function join($glue = null)
    {
        return implode($glue, $this->values);
    }

    /**
     * @return mixed
     */
    public function pop()
    {
        if ($this->isEmpty()) {
            throw new UnderflowException();
        }

        $value = array_pop($this->values);
        $this->checkCapacity();

        return $value;
    }

    /**
     * @param $values
     */
    private function pushAll($values)
    {
        foreach ($values as $value) {
            $this->values[] = $value;
        }

        $this->checkCapacity();
    }

    /**
     * @param mixed ...$values
     */
    public function push(...$values)
    {
        $this->pushAll($values);
    }

    /**
     * @param callable $callback
     * @param null     $initial
     * @return mixed
     */
    public function reduce(callable $callback, $initial = null)
    {
        return array_reduce($this->values, $callback, $initial);
    }

    /**
     * @param int $index
     * @return mixed
     */
    public function remove($index)
    {
        if ( ! $this->validIndex($index)) {
            throw new OutOfRangeException();
        }

        $value = array_splice($this->values, $index, 1, null)[0];
        $this->checkCapacity();

        return $value;
    }

    /**
     *
     */
    public function reverse()
    {
        $this->values = array_reverse($this->values);
    }

    /**
     * @return Sequence
     */
    public function reversed()
    {
        return new self(array_reverse($this->values));
    }

    /**
     * Converts negative or large rotations into the minimum positive number
     * of rotations required to rotate the sequence by a given $r.
     *
     * @param int $r
     * @return int
     */
    private function normalizeRotations($r)
    {
        $n = count($this);

        if ($n < 2) return 0;
        if ($r < 0) return $n - (abs($r) % $n);

        return $r % $n;
    }

    /**
     * @param int $rotations
     */
    public function rotate($rotations)
    {
        for ($r = $this->normalizeRotations($rotations); $r > 0; $r--) {
            array_push($this->values, array_shift($this->values));
        }
    }

    /**
     * @param int $index
     * @param     $value
     */
    public function set($index, $value)
    {
        if ( ! $this->validIndex($index)) {
            throw new OutOfRangeException();
        }

        $this->values[$index] = $value;
    }

    /**
     * @return mixed
     */
    public function shift()
    {
        if ($this->isEmpty()) {
            throw new UnderflowException();
        }

        $value = array_shift($this->values);
        $this->checkCapacity();

        return $value;
    }

    /**
     * @param callable|null $comparator
     */
    public function sort(callable $comparator = null)
    {
        if ($comparator) {
            usort($this->values, $comparator);
        } else {
            sort($this->values);
        }
    }

    /**
     * @param callable|null $comparator
     * @return Sequence
     */
    public function sorted(callable $comparator = null)
    {
        $copy = $this->copy();
        $copy->sort($comparator);
        return $copy;
    }

    /**
     * @return float|int
     */
    public function sum()
    {
        return array_sum($this->values);
    }

    /**
     * @param mixed ...$values
     */
    public function unshift(...$values)
    {
        if ($values) {
            $this->values = array_merge($values, $this->values);
            $this->checkCapacity();
        }
    }

    /**
     * @param int $index
     * @return bool
     */
    private function validIndex($index)
    {
        return $index >= 0 && $index < count($this);
    }

    /**
     * @return \Generator
     */
    public function getIterator()
    {
        foreach ($this->values as $value) {
            yield $value;
        }
    }

    /**
     *
     */
    public function clear()
    {
        $this->values = [];
        $this->capacity = self::MIN_CAPACITY;
    }

    /**
     * @param $offset
     * @param $value
     */
    public function offsetSet($offset, $value)
    {
        if ($offset === null) {
            $this->push($value);
        } else {
            $this->set($offset, $value);
        }
    }

    /**
     * @param $offset
     * @return mixed
     */
    public function &offsetGet($offset)
    {
        if ( ! $this->validIndex($offset)) {
            throw new OutOfRangeException();
        }

        return $this->values[$offset];
    }

    /**
     * @param $offset
     */
    public function offsetUnset($offset)
    {
        if (is_integer($offset) && $this->validIndex($offset)) {
            $this->remove($offset);
        }
    }

    /**
     * @param $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return is_integer($offset)
            && $this->validIndex($offset)
            && $this->get($offset) !== null;
    }
}
