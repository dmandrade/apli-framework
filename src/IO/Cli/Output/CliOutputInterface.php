<?php

namespace Apli\IO\Cli\Output;

/**
 * Class CliOutputInterface.
 */
interface CliOutputInterface
{
    /**
     * Write a string to standard output.
     *
     * @param string $text The text to display.
     *
     * @return CliOutputInterface Instance of $this to allow chaining.
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
