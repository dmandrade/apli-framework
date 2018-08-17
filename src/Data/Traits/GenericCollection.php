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
     * Returns whether the collection is empty.
     *
     * @return bool whether the collection is empty.
     */
    public function isEmpty()
    {
        return count($this) === 0;
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
        return $this->toArray();
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
     * Returns an array representation of the collection.
     *
     * The format of the returned array is implementation-dependent. Some
     * implementations may throw an exception if an array representation
     * could not be created (for example when object are used as keys).
     *
     * @return array
     */
    abstract public function toArray();

    /**
     * Returns a string representation of the collection, which is invoked when
     * the collection is converted to a string.
     */
    public function __toString()
    {
        return 'object(' . get_class($this) . ')';
    }

}
