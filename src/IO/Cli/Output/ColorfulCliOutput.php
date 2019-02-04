<?php
/**
 *  Copyright (c) 2018 Danilo Andrade
 *
 *  This file is part of the apli project.
 *
 * @project apli
 * @file ColorfulCliOutput.php
 * @author Danilo Andrade <danilo@webbingbrasil.com.br>
 * @date 27/08/18 at 10:27
 */

namespace Apli\IO\Cli\Output;

use Apli\IO\Cli\Color\ColorProcessor;
use Apli\IO\Cli\Color\DefaultColorProcessor;

/**
 * Simple Cli Output.
 */
class ColorfulCliOutput extends AbstractCliOutput
{
    /**
     * Color processing object.
     *
     * @var ColorProcessor
     */
    protected $processor;

    /**
     * Constructor.
     *
     * @param ColorProcessor $processor The output processor.
     */
    public function __construct(ColorProcessor $processor = null)
    {
        $this->setProcessor(($processor instanceof ColorProcessor) ? $processor : new DefaultColorProcessor());
    }

    /**
     * Write a string to standard output.
     *
     * @param string $text The text to display.
     * @param bool   $nl True (default) to append a new line at the end of the output string.
     *
     * @return ColorfulCliOutput Instance of $this to allow chaining.
     */
    public function out($text = '', $nl = true)
    {
        fwrite($this->outputStream, $this->getProcessor()->process($text).($nl ? "\n" : null));

        return $this;
    }

    /**
     * Get a processor.
     *
     * @throws \RuntimeException
     *
     * @return ColorProcessor
     */
    public function getProcessor()
    {
        if ($this->processor) {
            return $this->processor;
        }

        throw new \RuntimeException('A ColorProcessorInterface object has not been set.');
    }

    /**
     * Set a processor.
     *
     * @param ColorProcessor $processor The output processor.
     *
     * @return ColorfulCliOutput Instance of $this to allow chaining.
     */
    public function setProcessor(ColorProcessor $processor)
    {
        $this->processor = $processor;

        return $this;
    }

    /**
     * Write a string to standard error output.
     *
     * @param string $text The text to display.
     * @param bool   $nl True (default) to append a new line at the end of the output string.
     *
     * @return $this
     */
    public function err($text = '', $nl = true)
    {
        fwrite($this->errorStream, $this->processor->process($text).($nl ? "\n" : null));

        return $this;
    }
}
