<?php

namespace Apli\IO\Cli\Output;

/**
 * Class SimpleCliOutput
 */
class SimpleCliOutput extends AbstractCliOutput
{
    /**
     * Write a string to standard output
     *
     * @param   string $text The text to display.
     * @param   boolean $nl True (default) to append a new line at the end of the output string.
     *
     * @return  SimpleCliOutput  Instance of $this to allow chaining.
     */
    public function out($text = '', $nl = true)
    {
        fwrite($this->outputStream, $text.($nl ? "\n" : null));

        return $this;
    }

    /**
     * Write a string to standard error output.
     *
     * @param   string $text The text to display.
     * @param   boolean $nl True (default) to append a new line at the end of the output string.
     *
     * @return $this
     */
    public function err($text = '', $nl = true)
    {
        fwrite($this->errorStream, $text.($nl ? "\n" : null));

        return $this;
    }
}

