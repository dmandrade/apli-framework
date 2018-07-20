<?php

namespace Apli\IO\Cli\Color;

/**
 * The NullColorProcessor class.
 */
class NoColorProcessor extends ColorProcessor
{
    /**
     * Flag to remove color codes from the output.
     *
     * @var bool
     */
    public $noColors = true;
}
