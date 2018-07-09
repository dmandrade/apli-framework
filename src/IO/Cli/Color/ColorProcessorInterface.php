<?php

namespace Apli\IO\Cli\Color;

/**
 * Class ProcessorInterface.
 */
interface ColorProcessorInterface
{
    /**
     * Process the provided output into a string.
     *
     * @param   string $output
     *
     * @return  string
     */
    public function process($output);

    /**
     * Add a style.
     *
     * @param   string $name The style name.
     * @param   ColorStyle $style The color style.
     *
     * @return  ColorProcessor  Instance of $this to allow chaining.
     *
     */
    public function addStyle($name, ColorStyle $style);

    /**
     * Method to set property noColors
     *
     * @param   boolean $noColors
     *
     * @return  static  Return self to support chaining.
     */
    public function setNoColors($noColors);
}
