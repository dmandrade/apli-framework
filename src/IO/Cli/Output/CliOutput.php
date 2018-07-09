<?php

namespace Apli\IO\Cli\Output;

use Apli\IO\Cli\Color\ColorProcessor;
use Apli\IO\Cli\Color\ColorProcessorInterface;

/**
 * Simple Cli Output
 */
class CliOutput extends AbstractCliOutput implements ColorfulOutputInterface
{
    /**
     * Color processing object
     *
     * @var    ColorProcessorInterface
     */
    protected $processor;

    /**
     * Constructor
     *
     * @param   ColorProcessorInterface $processor The output processor.
     */
    public function __construct(ColorProcessorInterface $processor = null)
    {
        $this->setProcessor(($processor instanceof ColorProcessorInterface) ? $processor : new ColorProcessor);
    }

    /**
     * Write a string to standard output
     *
     * @param   string $text The text to display.
     * @param   boolean $nl True (default) to append a new line at the end of the output string.
     *
     * @return  CliOutput  Instance of $this to allow chaining.
     */
    public function out($text = '', $nl = true)
    {
        fwrite($this->outputStream, $this->getProcessor()->process($text) . ($nl ? "\n" : null));

        return $this;
    }

    /**
     * Get a processor
     *
     * @return  ColorProcessorInterface
     *
     * @throws  \RuntimeException
     */
    public function getProcessor()
    {
        if ($this->processor) {
            return $this->processor;
        }

        throw new \RuntimeException('A ColorProcessorInterface object has not been set.');
    }

    /**
     * Set a processor
     *
     * @param   ColorProcessorInterface $processor The output processor.
     *
     * @return  CliOutput  Instance of $this to allow chaining.
     */
    public function setProcessor(ColorProcessorInterface $processor)
    {
        $this->processor = $processor;

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
        fwrite($this->errorStream, $this->processor->process($text) . ($nl ? "\n" : null));

        return $this;
    }
}

