<?php
/**
 *  Copyright (c) 2018 Danilo Andrade.
 *
 *  This file is part of the apli project.
 *
 * @project apli
 * @file CliInput.php
 *
 * @author Danilo Andrade <danilo@webbingbrasil.com.br>
 * @date 27/08/18 at 10:27
 */

namespace Apli\IO\Cli\Input;

/**
 * Interface CliInputInterface.
 */
interface CliInput
{
    /**
     * Get a value from standard input.
     *
     * @return string The input string from standard input.
     */
    public function in();

    /**
     * Gets a value from the input data.
     *
     * @param string $name    Name of the value to get.
     * @param mixed  $default Default value to return if variable does not exist.
     *
     * @return mixed The filtered input value.
     */
    public function get($name, $default = null);

    /**
     * Sets a value.
     *
     * @param string $name  Name of the value to set.
     * @param mixed  $value Value to assign to the input.
     *
     * @return CliInput
     */
    public function set($name, $value);

    /**
     * Gets an array of values from the request.
     *
     * @return mixed The filtered input data.
     */
    public function all();

    /**
     * getArgument.
     *
     * @param int   $offset
     * @param mixed $default
     *
     * @return mixed
     */
    public function getArgument($offset, $default = null);

    /**
     * getCalledScript.
     *
     * @return string
     */
    public function getCalledScript();
}
