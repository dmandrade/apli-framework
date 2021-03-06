<?php
/**
 *  Copyright (c) 2018 Danilo Andrade.
 *
 *  This file is part of the apli project.
 *
 * @project apli
 * @file Vector.php
 *
 * @author Danilo Andrade <danilo@webbingbrasil.com.br>
 * @date 27/08/18 at 10:26
 */

namespace Apli\Data;

/**
 * Class Vector is a sequence of values in a contiguous buffer that grows and
 * shrinks automatically.
 */
final class Vector implements Sequence
{
    use Traits\GenericCollection;
    use Traits\GenericSequence;
    use Traits\Capacity;

    const MIN_CAPACITY = 8;

    /**
     * @return float
     */
    protected function getGrowthFactor()
    {
        return 1.5;
    }

    /**
     * @return bool whether capacity should be increased.
     */
    protected function shouldIncreaseCapacity()
    {
        return count($this) > $this->capacity;
    }
}
