<?php
/**
 *  Copyright (c) 2018 Danilo Andrade.
 *
 *  This file is part of the apli project.
 *
 * @project apli
 * @file IOInterface.php
 *
 * @author Danilo Andrade <danilo@webbingbrasil.com.br>
 * @date 27/08/18 at 10:27
 */

namespace Apli\IO\Cli;

/**
 * Interface IO.
 */
interface IO extends \IteratorAggregate, \ArrayAccess, \Serializable, \Countable, \JsonSerializable
{
    /**
     * Write a string to standard output.
     *
     * @param string $text The text to display.
     * @param bool   $nl   True (default) to append a new line at the end of the output string.
     *
     * @return IO Instance of $this to allow chaining.
     */
    public function out($text = '', $nl = true);

    /**
     * Get a value from standard input.
     *
     * @return string The input string from standard input.
     */
    public function in();

    /**
     * Write a string to standard error output.
     *
     * @param string $text The text to display.
     *
     * @return IO
     */
    public function err($text = '');

    /**
     * Gets a value from the input data.
     *
     * @param string $name    Name of the value to get.
     * @param mixed  $default Default value to return if variable does not exist.
     *
     * @return mixed The filtered input value.
     */
    public function getOption($name, $default = null);

    /**
     * Sets a value.
     *
     * @param string $name  Name of the value to set.
     * @param mixed  $value Value to assign to the input.
     *
     * @return void
     */
    public function setOption($name, $value);

    /**
     * getOptions.
     *
     * @return string[]
     */
    public function getOptions();

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
     * setArgument.
     *
     * @param int   $offset
     * @param mixed $value
     *
     * @return IO
     */
    public function setArgument($offset, $value);

    /**
     * getArguments.
     *
     * @return string[]
     */
    public function getArguments();

    /**
     * getExecuted.
     *
     * @return mixed
     */
    public function getCalledScript();
}
