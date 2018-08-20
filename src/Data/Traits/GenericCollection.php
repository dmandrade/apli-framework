<?php
namespace Apli\Data\Traits;

use Apli\Data\Collection;
use Apli\Data\Map;
use Apli\Support\Hashable;
use Apli\Support\Traits\HashableTrait;

/**
 * Trait GenericCollection
 * @package Apli\Data\Traits
 */
trait GenericCollection
{
    use HashableTrait;

    /**
     * Get all of the items in the collection.
     *
     * @return array
     */
    public function all()
    {
        return $this->toArray();
    }

    /**
     * Return the first item from collection
     *
     * @return Entry
     *
     * @throws UnderflowException
     */
    public function first()
    {
        if ($this->isEmpty()) {
            throw new UnderflowException();
        }

        $array = $this->all();
        return current($array);
    }

    /**
     * Return the last Entry from the Map
     *
     * @return Entry
     *
     * @throws UnderflowException
     */
    public function last()
    {
        if ($this->isEmpty()) {
            throw new UnderflowException();
        }

        $array = $this->all();
        return end($array);
    }

    /**
     * Determine if the given value is callable, but not a string.
     *
     * @param  mixed  $value
     * @return bool
     */
    protected function useAsCallable($value)
    {
        return ! is_string($value) && is_callable($value);
    }

    /**
     * Execute a callback over each item.
     *
     * @param  callable  $callback
     * @return $this
     */
    public function each(callable $callback)
    {
        foreach ($this as $key => $item) {
            if ($callback($item, $key) === false) {
                break;
            }
        }

        return $this;
    }

    /**
     * Run a map over each of the items.
     *
     * @param  callable  $callback
     * @return static
     */
    public function map(callable $callback)
    {
        $itens = [];

        foreach ($this as $key => $value) {
            $newKey = $key;
            $newValue = $callback($value, $key);

            if(is_array($newValue)) {
                $newKey = key($newValue);
                $newValue = current($newValue);
            }

            $itens[$newKey] = $newValue;
        }

        return new static($itens);
    }

    /**
     * Create a new collection consisting of every n-th element.
     *
     * @param  int  $step
     * @param  int  $offset
     * @return static
     */
    public function every($step, $offset = 0)
    {
        $new = [];

        $position = 0;

        foreach ($this as $key => $item) {
            if ($position % $step === $offset) {
                $new[$key] = $item;
            }

            $position++;
        }

        return new static($new);
    }

    /**
     * Run a filter over each of the items.
     *
     * @param  callable|null  $callback
     * @return static
     */
    public function filter(callable $callback = null)
    {
        $filtered = new self();

        foreach ($this as $key => $value) {
            if ($callback ? $callback($value, $key) : $value) {
                $filtered->set($key, $value);
            }
        }

        return $filtered;
    }

    /**
     * Create a collection of all elements that do not pass a given truth test.
     *
     * @param  callable|mixed  $callback
     * @return static
     */
    public function reject($callback)
    {
        if ($this->useAsCallable($callback)) {
            return $this->filter(function ($value, $key) use ($callback) {
                return ! $callback($value, $key);
            });
        }

        return $this->filter(function ($item) use ($callback) {
            return $item != $callback;
        });
    }

    /**
     * Slice the underlying collection array.
     *
     * @param  int  $offset
     * @param  int  $length
     * @return static
     */
    public function slice($offset, $length = null)
    {
        return new static(array_slice($this->all(), $offset, $length, true));
    }

    /**
     * Split a collection into a certain number of groups.
     *
     * @param  int  $numberOfGroups
     * @return static
     */
    public function split($numberOfGroups)
    {
        if ($this->isEmpty()) {
            return new static;
        }

        $groupSize = ceil($this->count() / $numberOfGroups);

        return $this->chunk($groupSize);
    }

    /**
     * Splice a portion of the underlying collection array.
     *
     * @param  int  $offset
     * @param  int|null  $length
     * @param  mixed  $replacement
     * @return static
     */
    public function splice($offset, $length = null, $replacement = [])
    {
        if (func_num_args() == 1) {
            return new static(array_splice($this->all(), $offset));
        }

        return new static(array_splice($this->all(), $offset, $length, $replacement));
    }

    /**
     * Take the first or last {$limit} items.
     *
     * @param  int  $limit
     * @return static
     */
    public function take($limit)
    {
        if ($limit < 0) {
            return $this->slice($limit, abs($limit));
        }

        return $this->slice(0, $limit);
    }

    /**
     * Chunk the underlying collection array.
     *
     * @param  int  $size
     * @return static
     */
    public function chunk($size)
    {
        if ($size <= 0) {
            return new static;
        }

        $chunks = [];

        foreach (array_chunk($this->all(), $size, true) as $chunk) {
            $chunks[] = new static($chunk);
        }

        return new static($chunks);
    }

    /**
     * Get the items in the collection that are not present in the given items.
     *
     * @param  mixed  $items
     * @return static
     */
    public function diff($items)
    {
        $array = array_values($this->getArrayableItems($items));
        return $this->filter(function($value) use ($array) {
            foreach ($array as $otherValue) {
                if($otherValue === $value) {
                    return false;
                }
            }

            return true;
        });
    }

    /**
     * Get the items in the collection whose keys are not present in the given items.
     *
     * @param  mixed  $items
     * @return static
     */
    public function diffKeys($items)
    {
        $array = array_keys($this->getArrayableItems($items));
        return $this->filter(function($value, $key) use ($array) {
            if (is_object($key) && $key instanceof Hashable) {
                $key = $key->hash();
            }

            foreach ($array as $otherKey) {
                if($otherKey === $key) {
                    return false;
                }
            }

            return true;
        });
    }

    /**
     * @param mixed ...$values
     * @return bool
     */
    public function contains(...$values)
    {
        foreach ($values as $value) {
            if ($this->search($value) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param mixed ...$values
     * @return bool
     */
    public function containsStrict(...$values)
    {
        foreach ($values as $value) {
            if ($this->searchStrict($value) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns the key of a given value, or false if it could not be found.
     *
     * @param mixed $value
     *
     * @return false|int|string
     */
    public function searchStrict($value)
    {
        if (! $this->useAsCallable($value)) {
            $value = function($itemValue) use ($value) {
                return $itemValue === $value;
            };
        }

        return $this->search($value);
    }

    /**
     * Returns the key of a given value, or false if it could not be found.
     *
     * @param mixed $value
     *
     * @return false|int|string
     */
    public function search($value)
    {
        if (! $this->useAsCallable($value)) {
            $value = function($itemValue) use ($value) {
                return $itemValue === $value;
            };
        }

        return current(array_flip($this->filter($value)->toArray()));
    }

    /**
     * Returns whether the collection is empty.
     *
     * @return bool whether the collection is empty.
     */
    public function isEmpty()
    {
        return $this->count() === 0;
    }
    /**
     * Returns whether the collection is  notempty.
     *
     * @return bool whether the collection is not empty.
     */
    public function isNotEmpty()
    {
        return!$this->isEmpty();
    }

    /**
     * Returns a representation that can be natively converted to JSON, which is
     * called when invoking json_encode.
     *
     * @return mixed
     *
     * @see JsonSerializable
     */
    public function jsonSerialize()
    {
        return array_map(function ($value) {
            if ($value instanceof JsonSerializable) {
                return $value->jsonSerialize();
            } elseif ($value instanceof Jsonable) {
                return json_decode($value->toJson(), true);
            } elseif ($value instanceof Arrayable) {
                return $value->toArray();
            } else {
                return $value;
            }
        }, $this->all());
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Creates a shallow copy of the object.
     *
     * @return self|Collection
     */
    public function copy()
    {
        return new self($this);
    }

    /**
     * Returns a string representation of the collection, which is invoked when
     * the collection is converted to a string.
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * Results array of items from Collection or Arrayable.
     *
     * @param  mixed  $items
     * @return array
     */
    protected function getArrayableItems($items)
    {
        if (is_array($items)) {
            return $items;
        } elseif ($items instanceof self) {
            return $items->all();
        } elseif ($items instanceof Traversable) {
            return iterator_to_array($items);
        } elseif ($items instanceof Arrayable) {
            return $items->toArray();
        } elseif ($items instanceof Jsonable) {
            return json_decode($items->toJson(), true);
        } elseif ($items instanceof JsonSerializable) {
            return $items->jsonSerialize();
        }

        return (array) $items;
    }
}
