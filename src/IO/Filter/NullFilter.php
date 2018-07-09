<?php

namespace Apli\IO\Filter;

/**
 * The NullFilter class.
 */
class NullFilter
{
    /**
     * clean
     *
     * @param string $source
     * @param string|callable|object $filter
     *
     * @return  mixed
     */
    public function clean($source, $filter = 'string')
    {
        return $source;
    }
}
