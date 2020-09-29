<?php
/**
 *  Copyright (c) 2018 Danilo Andrade.
 *
 *  This file is part of the apli project.
 *
 * @project apli
 * @file Capacity.php
 *
 * @author Danilo Andrade <danilo@webbingbrasil.com.br>
 * @date 27/08/18 at 10:27
 */

namespace Apli\Data\Traits;

/**
 * Trait Capacity.
 */
trait Capacity
{
    /**
     * @var int internal capacity
     */
    private $capacity = self::MIN_CAPACITY;

    /**
     * Returns the current capacity.
     *
     * @return int
     */
    public function capacity()
    {
        return $this->capacity;
    }

    /**
     * Ensures that enough memory is allocated for a specified capacity.
     *
     * @param int $capacity The number of values for which capacity should be allocated.
     */
    public function allocate($capacity)
    {
        $this->capacity = max($capacity, $this->capacity);
    }

    /**
     * @return float the structures growth factor.
     */
    protected function getGrowthFactor()
    {
        return 2;
    }

    /**
     * Checks and adjusts capacity if required.
     */
    protected function checkCapacity()
    {
        if ($this->shouldIncreaseCapacity()) {
            $this->increaseCapacity();
        } else {
            if ($this->shouldDecreaseCapacity()) {
                $this->decreaseCapacity();
            }
        }
    }

    /**
     * @return bool whether capacity should be increased.
     */
    protected function shouldDecreaseCapacity()
    {
        return count($this) <= $this->capacity * $this->getTruncateThreshold();
    }

    /**
     * @return float the ratio between size and capacity when capacity should be
     *               decreased.
     */
    protected function getTruncateThreshold()
    {
        return 0.25;
    }

    /**
     * Called when capacity should be decrease if it drops below a threshold.
     */
    protected function decreaseCapacity()
    {
        $this->capacity = max(self::MIN_CAPACITY, $this->capacity * $this->getDecayFactor());
    }

    /**
     * @return float to multiply by when decreasing capacity.
     */
    protected function getDecayFactor()
    {
        return 0.5;
    }

    /**
     * Called when capacity should be increased to accommodate new values.
     */
    protected function increaseCapacity()
    {
        $this->capacity = max($this->count(), $this->capacity * $this->getGrowthFactor());
    }

    /**
     * @return bool whether capacity should be increased.
     */
    protected function shouldIncreaseCapacity()
    {
        return count($this) >= $this->capacity;
    }
}
