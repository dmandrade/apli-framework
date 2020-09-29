<?php
/**
 *  Copyright (c) 2018 Danilo Andrade.
 *
 *  This file is part of the apli project.
 *
 * @project apli
 * @file NullFilter.php
 *
 * @author Danilo Andrade <danilo@webbingbrasil.com.br>
 * @date 27/08/18 at 10:26
 */

namespace Apli\IO\Filter;

/**
 * The NullFilter class.
 */
class NullFilter
{
    /**
     * clean.
     *
     * @param string                 $source
     * @param string|callable|object $filter
     *
     * @return mixed
     */
    public function clean($source, $filter = 'string')
    {
        return $source;
    }
}
