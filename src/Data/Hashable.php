<?php
namespace Apli\Data;

/**
 * Interface Hashable allows objects to be used as keys.
 *
 * @package Apli\Data
 */
interface Hashable
{

    /**
     * Produces a scalar value to be used as the object's hash, which determines
     * where it goes in the hash table. While this value does not have to be
     * unique, objects which are equal must have the same hash value.
     *
     * @return mixed
     */
    function hash();

    /**
     * Determines if two objects should be considered equal. Both objects will
     * be instances of the same class but may not be the same instance.
     *
     * @param Hashable $obj Instance of the same class to compare to.
     * @return bool
     */
    function equals($obj);
}
