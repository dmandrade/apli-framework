<?php
/**
 * Created by PhpStorm.
 * User: Danilo
 * Date: 02/08/2018
 * Time: 10:16
 */

namespace Apli\Data;

/**
 * Class Deque is a sequence of values in a contiguous buffer
 * that grows and shrinks automatically.
 * @package Apli\Data
 */
class Deque implements \IteratorAggregate, \ArrayAccess, Sequence
{
    use Traits\GenericCollection;
    use Traits\GenericSequence;
    use Traits\SquaredCapacity;

    const MIN_CAPACITY = 8;
}
