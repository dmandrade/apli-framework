<?php
/**
 *  Copyright (c) 2018 Danilo Andrade
 *
 *  This file is part of the apli project.
 *
 *  @project apli
 *  @file NoColorProcessor.php
 *  @author Danilo Andrade <danilo@webbingbrasil.com.br>
 *  @date 27/07/18 at 10:11
 */

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
