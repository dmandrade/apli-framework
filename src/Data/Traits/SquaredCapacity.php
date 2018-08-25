<?php
/**
 *  Copyright (c) 2018 Danilo Andrade
 *
 *  This file is part of the apli project.
 *
 *  @project apli
 *  @file SquaredCapacity.php
 *  @author Danilo Andrade <danilo@webbingbrasil.com.br>
 *  @date 30/07/18 at 10:09
 */

namespace Apli\Data\Traits;

/**
 * Trait SquaredCapacity
 * @package Apli\Data\Traits
 */
trait SquaredCapacity
{
    use Capacity;

    /**
     * Rounds an integer to the next power of two if not already a power of two.
     *
     * @param int $capacity
     *
     * @return int
     */
    private function square($capacity)
    {
        return pow(2, ceil(log($capacity, 2)));
    }

    /**
     * Ensures that enough memory is allocated for a specified capacity.
     *
     * @param int $capacity The number of values for which capacity should be allocated.
     */
    public function allocate($capacity)
    {
        $this->capacity = max($this->square($capacity), $this->capacity);
    }

    /**
     * Called when capacity should be increased to accommodate new values.
     */
    protected function increaseCapacity()
    {
        $this->capacity = $this->square(max(count($this) + 1, $this->capacity * $this->getGrowthFactor()));
    }
}
