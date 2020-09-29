<?php
/**
 *  Copyright (c) 2018 Danilo Andrade.
 *
 *  This file is part of the apli project.
 *
 * @project apli
 * @file ColorProcessorInterface.php
 *
 * @author Danilo Andrade <danilo@webbingbrasil.com.br>
 * @date 27/08/18 at 10:27
 */

namespace Apli\IO\Cli\Color;

/**
 * Class ProcessorInterface.
 */
interface ColorProcessor
{
    /**
     * Process the provided output into a string.
     *
     * @param string $output
     *
     * @return string
     */
    public function process($output);

    /**
     * Add a style.
     *
     * @param string     $name  The style name.
     * @param ColorStyle $style The color style.
     *
     * @return DefaultColorProcessor Instance of $this to allow chaining.
     */
    public function addStyle($name, ColorStyle $style);

    /**
     * Method to set property noColors.
     *
     * @param bool $noColors
     *
     * @return static Return self to support chaining.
     */
    public function setNoColors($noColors);
}
