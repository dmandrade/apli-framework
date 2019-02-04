<?php
/**
 *  Copyright (c) 2018 Danilo Andrade
 *
 *  This file is part of the apli project.
 *
 * @project apli
 * @file CliOutput.php
 * @author Danilo Andrade <danilo@webbingbrasil.com.br>
 * @date 27/08/18 at 10:27
 */

namespace Apli\IO\Cli\Output;

/**
 * Class CliOutputInterface.
 */
interface CliOutput
{
    /**
     * Write a string to standard output.
     *
     * @param string $text The text to display.
     *
     * @return CliOutput Instance of $this to allow chaining.
     */
    public function out($text = '');

    /**
     * Write a string to standard error output.
     *
     * @param string $text The text to display.
     *
     * @return $this
     */
    public function err($text = '');
}
