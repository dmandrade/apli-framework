<?php
/**
 *  Copyright (c) 2018 Danilo Andrade
 *
 *  This file is part of the apli project.
 *
 * @project apli
 * @file AbstractCliOutput.php
 * @author Danilo Andrade <danilo@webbingbrasil.com.br>
 * @date 27/08/18 at 10:27
 */

namespace Apli\IO\Cli\Output;

/**
 * Class AbstractCliOutput.
 */
abstract class AbstractCliOutput implements CliOutput
{
    /**
     * Property outStream.
     *
     * @var resource
     */
    protected $outputStream = STDOUT;

    /**
     * Property errorStream.
     *
     * @var resource
     */
    protected $errorStream = STDERR;

    /**
     * getOutStream.
     *
     * @return resource
     */
    public function getOutputStream()
    {
        return $this->outputStream;
    }

    /**
     * setOutStream.
     *
     * @param resource $outStream
     *
     * @return static Return self to support chaining.
     */
    public function setOutputStream($outStream)
    {
        $this->outputStream = $outStream;

        return $this;
    }

    /**
     * Method to get property ErrorStream.
     *
     * @return resource
     */
    public function getErrorStream()
    {
        return $this->errorStream;
    }

    /**
     * Method to set property errorStream.
     *
     * @param resource $errorStream
     *
     * @return static Return self to support chaining.
     */
    public function setErrorStream($errorStream)
    {
        $this->errorStream = $errorStream;

        return $this;
    }
}
