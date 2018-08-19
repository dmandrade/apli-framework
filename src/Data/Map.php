<?php
namespace Apli\Data;

use Apli\Support\Hashable;
use OutOfBoundsException;
use OutOfRangeException;
use Traversable;

/**
 * A Map is a sequential collection of key-value entries, almost identical to an
 * array used in a similar context. Keys can be any type, but must be unique.
 *
 * @package Ds
 */
final class Map implements Collection
{
    use Traits\GenericCollection;
    use Traits\SquaredCapacity;

    const MIN_CAPACITY = 8;

    /**
     * @var array internal array to store entries
     */
    private $entries = [];

    /**
     * Creates a new instance.
     *
     * @param array|\Traversable|null $values
     */
    public function __construct($values = null)
    {
        if (func_num_args()) {
            $this->putAll($values);
        }
    }

    /**
     * Updates all values by applying a callback function to each value.
     *
     * @param callable $callback Accepts two arguments: key and value, should
     *                           return what the updated value will be.
     */
    public function apply(callable $callback)
    {
        foreach ($this->entries as &$entry) {
            $entry->value = $callback($entry->key, $entry->value);
        }
    }

    /**
     * Reset object to initial state
     */
    public function clear()
    {
        $this->entries = [];
        $this->capacity = self::MIN_CAPACITY;
    }

    /**
     * Return the entry at a specified position in the Map
     *
     * @param int $position
     *
     * @return Entry
     *
     * @throws OutOfRangeException
     */
    public function skip($position)
    {
        if ($position < 0 || $position >= count($this->entries)) {
            throw new OutOfRangeException();
        }

        return $this->entries[$position]->copy();
    }

    /**
     * Returns the result of associating all keys of a given traversable object
     * or array with their corresponding values, as well as those of this map.
     *
     * @param array|\Traversable $values
     *
     * @return Map
     */
    public function merge($values)
    {
        $merged = new self($this);
        $merged->putAll($values);
        return $merged;
    }

    /**
     * Creates a new map containing the entries of the current instance whose keys
     * are also present in the given map. In other words, returns a copy of the
     * current map with all keys removed that are not also in the other map.
     *
     * @param Map $map The other map.
     *
     * @return Map A new map containing the entries of the current instance
     *                 whose keys are also present in the given map. In other
     *                 words, returns a copy of the current map with all keys
     *                 removed that are not also in the other map.
     */
    public function intersect(Map $map)
    {
        return $this->filter(function($key) use ($map) {
            return $map->hasKey($key);
        });
    }

    /**
     * Determines whether two keys are equal.
     *
     * @param mixed $a
     * @param mixed $b
     *
     * @return bool
     */
    private function keysAreEqual($a, $b)
    {
        if (is_object($a) && $a instanceof Hashable) {
            return get_class($a) === get_class($b) && $a->equals($b);
        }

        return $a === $b;
    }

    /**
     * Attempts to look up a key in the table.
     *
     * @param $key
     *
     * @return Entry|null
     */
    private function lookupKey($key)
    {
        foreach ($this->entries as $entry) {
            if ($this->keysAreEqual($entry->key, $key)) {
                return $entry;
            }
        }

        return null;
    }

    /**
     * Attempts to look up a key in the table.
     *
     * @param $value
     *
     * @return Entry|null
     */
    private function lookupValue($value)
    {
        foreach ($this->entries as $entry) {
            if ($entry->value === $value) {
                return $entry;
            }
        }

        return null;
    }

    /**
     * Returns whether an association a given key exists.
     *
     * @param mixed $key
     *
     * @return bool
     */
    public function hasKey($key)
    {
        return $this->lookupKey($key) !== null;
    }

    /**
     * Returns whether an association for a given value exists.
     *
     * @param mixed $value
     *
     * @return bool
     */
    public function hasValue($value)
    {
        return $this->lookupValue($value) !== null;
    }

    /**
     * Return a count of itens in table
     * @return int
     */
    public function count()
    {
        return count($this->entries);
    }

    /**
     * Returns the value associated with a key, or an optional default if the
     * key is not associated with a value.
     *
     * @param mixed $key
     * @param mixed $default
     *
     * @return mixed The associated value or fallback default if provided.
     *
     * @throws OutOfBoundsException if no default was provided and the key is
     *                               not associated with a value.
     */
    public function get($key, $default = null)
    {
        if (($entry = $this->lookupKey($key))) {
            return $entry->value;
        }

        // Check if a default was provided.
        if (func_num_args() === 1) {
            throw new OutOfBoundsException();
        }

        return $default;
    }

    /**
     * Returns a set of all the keys in the map.
     *
     * @return Set
     */
    public function keys()
    {
        $key = function($entry) {
            return $entry->key;
        };

        return new Set(array_map($key, $this->entries));
    }

    /**
     * Returns a new map using the results of applying a callback to each value.
     *
     * The keys will be equal in both maps.
     *
     * @param callable $callback Accepts two arguments: key and value, should
     *                           return what the updated value will be.
     *
     * @return Map
     */
    public function map(callable $callback)
    {
        $apply = function($entry) use ($callback) {
            return $callback($entry->key, $entry->value);
        };

        return new self(array_map($apply, $this->entries));
    }

    /**
     * Returns a sequence of entries representing all associations.
     *
     * @return Sequence
     */
    public function entries()
    {
        $copy = function(Entry $entry) {
            return $entry->copy();
        };

        return new Vector(array_map($copy, $this->entries));
    }

    /**
     * Associates a key with a value, replacing a previous association if there
     * was one.
     *
     * @param mixed $key
     * @param mixed $value
     */
    public function put($key, $value)
    {
        if (is_object($key) && $key instanceof Hashable === false) {
            throw new \Exception("Objects as key need to implement Hashable");
        }

        $entry = $this->lookupKey($key);

        if ($entry) {
            $entry->value = $value;

        } else {
            $this->checkCapacity();
            $this->entries[] = new Entry($key, $value);
        }
    }

    /**
     * Replaces the value at a given key in the collection with a new value.
     *
     * @param int   $key
     * @param mixed $value
     *
     * @throws \OutOfRangeException if the index is not in the range [0, size-1]
     */
    public function set($key, $value) {
        $this->put($key, $value);
    }

    /**
     * Creates associations for all keys and corresponding values of either an
     * array or iterable object.
     *
     * @param \Traversable|array $values
     */
    public function putAll($values)
    {
        foreach ($values as $key => $value) {
            $this->put($key, $value);
        }
    }

    /**
     * Iteratively reduces the map to a single value using a callback.
     *
     * @param callable $callback Accepts the carry, key, and value, and
     *                           returns an updated carry value.
     *
     * @param mixed|null $initial Optional initial carry value.
     *
     * @return mixed The carry value of the final iteration, or the initial
     *               value if the map was empty.
     */
    public function reduce(callable $callback, $initial = null)
    {
        $carry = $initial;

        foreach ($this->entries as $entry) {
            $carry = $callback($carry, $entry->key, $entry->value);
        }

        return $carry;
    }

    /**
     * Removes and returns the value at a given index in the sequence.
     *
     * @param int $index this index to remove.
     *
     * @return mixed the removed value.
     *
     * @throws \OutOfRangeException if the index is not in the range [0, size-1]
     */
    function delete($index)
    {
        $entry  = $this->entries[$index];
        $value = $entry->value;

        array_splice($this->entries, $index, 1, null);
        $this->checkCapacity();

        return $value;
    }

    /**
     * Removes a key's association from the map and returns the associated value
     * or a provided default if provided.
     *
     * @param mixed $key
     * @param mixed $default
     *
     * @return mixed The associated value or fallback default if provided.
     *
     * @throws \OutOfBoundsException if no default was provided and the key is
     *                               not associated with a value.
     */
    public function remove($key, $default = null)
    {
        foreach ($this->entries as $position => $entry) {
            if ($this->keysAreEqual($entry->key, $key)) {
                return $this->delete($position);
            }
        }

        // Check if a default was provided
        if (func_num_args() === 1) {
            throw new \OutOfBoundsException();
        }

        return $default;
    }

    /**
     * Reverse map entries.
     */
    public function reverse()
    {
        $this->entries = array_reverse($this->entries);
    }

    /**
     * Returns a reversed copy of the map.
     *
     * @return Map
     */
    public function reversed()
    {
        $reversed = new self();
        $reversed->entries = array_reverse($this->entries);

        return $reversed;
    }

    /**
     * Returns a sub-sequence of a given length starting at a specified offset.
     *
     * @param int $offset      If the offset is non-negative, the map will
     *                         start at that offset in the map. If offset is
     *                         negative, the map will start that far from the
     *                         end.
     *
     * @param int|null $length If a length is given and is positive, the
     *                         resulting set will have up to that many entries in
     *                         it. If the requested length results in an
     *                         overflow, only entries up to the end of the map
     *                         will be included.
     *
     *                         If a length is given and is negative, the map
     *                         will stop that many entries from the end.
     *
     *                        If a length is not provided, the resulting map
     *                        will contains all entries between the offset and
     *                        the end of the map.
     *
     * @return Map
     */
    public function slice($offset, $length = null)
    {
        $map = new self();

        if (func_num_args() === 1) {
            $slice = array_slice($this->entries, $offset);
        } else {
            $slice = array_slice($this->entries, $offset, $length);
        }

        foreach ($slice as $entry) {
            $map->put($entry->key, $entry->value);
        }

        return $map;
    }

    /**
     * Sorts the map in-place, based on an optional callable comparator.
     *
     * The map will be sorted by value.
     *
     * @param callable|null $comparator Accepts two values to be compared.
     */
    public function sort(callable $comparator = null)
    {
        if ($comparator) {
            usort($this->entries, function($a, $b) use ($comparator) {
                return $comparator($a->value, $b->value);
            });

        } else {
            usort($this->entries, function($a, $b) {
                if ($a->value == $b->value) {
                    return 0;
                }

                return ($a->value < $b->value) ? -1 : 1;
            });
        }
    }

    /**
     * Returns a sorted copy of the map, based on an optional callable
     * comparator. The map will be sorted by value.
     *
     * @param callable|null $comparator Accepts two values to be compared.
     *
     * @return Collection
     */
    public function sorted(callable $comparator = null)
    {
        $copy = $this->copy();
        $copy->sort($comparator);
        return $copy;
    }

    /**
     * Sorts the map in-place, based on an optional callable comparator.
     *
     * The map will be sorted by key.
     *
     * @param callable|null $comparator Accepts two keys to be compared.
     */
    public function ksort(callable $comparator = null)
    {
        if ($comparator) {
            usort($this->entries, function($a, $b) use ($comparator) {
                return $comparator($a->key, $b->key);
            });

        } else {
            usort($this->entries, function($a, $b) {
                if ($a->key == $b->key) {
                    return 0;
                }

                return ($a->key < $b->key) ? -1 : 1;
            });
        }
    }

    /**
     * Returns a sorted copy of the map, based on an optional callable
     * comparator. The map will be sorted by key.
     *
     * @param callable|null $comparator Accepts two keys to be compared.
     *
     * @return Map
     */
    public function ksorted(callable $comparator = null)
    {
        $copy = $this->copy();
        $copy->ksort($comparator);
        return $copy;
    }

    /**
     * Returns the sum of all values in the map.
     *
     * @return int|float The sum of all the values in the map.
     */
    public function sum()
    {
        return $this->values()->sum();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $array = [];

        foreach ($this->entries as $entry) {
            $key = $entry->key;

            if (is_object($key) && $key instanceof Hashable) {
                $key = $key->hash();
            }

            $array[$key] = $entry->value;
        }

        return $array;
    }

    /**
     * Returns a sequence of all the associated values in the Map.
     *
     * @return Sequence
     */
    public function values()
    {
        $value = function($entry) {
            return $entry->value;
        };

        return new Vector(array_map($value, $this->entries));
    }

    /**
     * Creates a new map that contains the entries of the current instance as well
     * as the entries of another map.
     *
     * @param Map $map The other map, to combine with the current instance.
     *
     * @return Map A new map containing all the entries of the current
     *                 instance as well as another map.
     */
    public function union(Map $map)
    {
        return $this->merge($map);
    }

    /**
     * Creates a new map using keys of either the current instance or of another
     * map, but not of both.
     *
     * @param Map $map
     *
     * @return Map A new map containing keys in the current instance as well
     *                 as another map, but not in both.
     */
    public function doXor(Map $map)
    {
        return $this->merge($map)->filter(function($key) use ($map) {
            return $this->hasKey($key) ^ $map->hasKey($key);
        });
    }

    /**
     * Retrieve a external iterator
     *
     * @return \Generator|Traversable
     */
    public function getIterator()
    {
        foreach ($this->entries as $entry) {
            yield $entry->key => $entry->value;
        }
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->put($offset, $value);
    }

    /**
     * @param mixed $offset
     * @return mixed
     * @throws OutOfBoundsException
     */
    public function &offsetGet($offset)
    {
        $entry = $this->lookupKey($offset);

        if ($entry) {
            return $entry->value;
        }

        throw new OutOfBoundsException();
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        $this->remove($offset, null);
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->get($offset, null) !== null;
    }
}
