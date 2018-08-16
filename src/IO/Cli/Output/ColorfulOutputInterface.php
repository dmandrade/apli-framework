<?php

namespace Apli\IO\Cli\Output;

use Apli\IO\Cli\Color\ColorProcessorInterface;

/**
 * The ColorfulOutputInterface class.
 */
interface ColorfulOutputInterface
{
    /**
     * Set a processor.
     *
     * @param ColorProcessorInterface $processor The output processor.
     *
     * @return CliOutput Instance of $this to allow chaining.
     */
    public function setProcessor(ColorProcessorInterface $processor);

    /**
     * Get a processor.
     *
     * @throws \RuntimeException
     *
     * @return ColorProcessorInterface
     */
    public function getProcessor();
}
