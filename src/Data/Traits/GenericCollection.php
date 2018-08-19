<?php
namespace Apli\Data\Traits;

use Apli\Data\Collection;

/**
 * Trait GenericCollection
 * @package Apli\Data\Traits
 */
trait GenericCollection
{
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


        $array = $this->toArray();
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

        $array = $this->toArray();
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
        foreach ($this as $key => $value) {
            if (call_user_func($callback, $value, $key) === false) {
                break;
            }
        }

        return $this;
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
     * Get the items in the collection that are not present in the given items.
     *
     * @param  mixed  $items
     * @return static
     */
    public function diff($items)
    {
        return new static(array_diff($this->toArray(), $this->getArrayableItems($items)));
    }

    /**
     * Get the items in the collection whose keys are not present in the given items.
     *
     * @param  mixed  $items
     * @return static
     */
    public function diffKeys($items)
    {
        return new static(array_diff_key($this->toArray(), $this->getArrayableItems($items)));
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
                return $itemValue == $value;
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
        }, $this->toArray());
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
        } elseif ($items instanceof Arrayable) {
            return $items->toArray();
        } elseif ($items instanceof Jsonable) {
            return json_decode($items->toJson(), true);
        } elseif ($items instanceof JsonSerializable) {
            return $items->jsonSerialize();
        } elseif ($items instanceof Traversable) {
            return iterator_to_array($items);
        }

        return (array) $items;
    }
}
