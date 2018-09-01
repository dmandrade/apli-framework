<?php
/**
 *  Copyright (c) 2018 Danilo Andrade
 *
 *  This file is part of the apli project.
 *
 *  @project apli
 *  @file AbstractCliApplication.php
 *  @author Danilo Andrade <danilo@webbingbrasil.com.br>
 *  @date 27/08/18 at 10:26
 */

/**
 * Created by PhpStorm.
 * User: Danilo
 * Date: 09/07/2018
 * Time: 15:15.
 */

namespace Apli\Application;

use Apli\IO\Cli\BasicIO;
use Apli\IO\Cli\IO;

/**
 * Simple class for a Aplí command line application.
 *
 * @property-read  IO $io
 */
abstract class AbstractCliApplication extends AbstractApplication
{
    /**
     * The CLI In/Out object.
     *
     * @var IO
     */
    protected $io = null;

    /**
     * Class constructor.
     *
     * @param IO $io An optional argument to provide dependency injection for the application's IO object.
     * @param param Map $config An optional argument to provide a Map object to be config.
     */
    public function __construct(IO $io = null, Map $config = null)
    {
        // Close the application if we are not executed from the command line.
        if (!defined('STDOUT') || !defined('STDIN') || !isset($_SERVER['argv'])) {
            $this->close();
        }

        $this->io = $io instanceof IO ? $io : new BasicIO();
        parent::__construct($config);

        // Set the execution datetime and timestamp;
        $this->set('execution.datetime', gmdate('Y-m-d H:i:s'));
        $this->set('execution.timestamp', time());

        // Set the current directory.
        $this->set('cwd', getcwd());
    }

    /**
     * Write a string to standard output.
     *
     * @param string $text The text to display.
     * @param bool   $nl True (default) to append a new line at the end of the output string.
     *
     * @return AbstractCliApplication Instance of $this to allow chaining.
     */
    public function out($text = '', $nl = true)
    {
        $this->io->out($text, $nl);

        return $this;
    }

    /**
     * Get a value from standard input.
     *
     * @return string The input string from standard input.
     */
    public function in()
    {
        return $this->io->in();
    }

    /**
     * Get the IO object.
     *
     * @return IO
     */
    public function getIO()
    {
        return $this->io;
    }

    /**
     * Set the IO object.
     *
     * @param IO $io The IO object.
     *
     * @return AbstractCliApplication Return self to support chaining.
     */
    public function setIO($io)
    {
        $this->io = $io;

        return $this;
    }

    /**
     * is utilized for reading data from inaccessible members.
     *
     * @param   $name string
     *
     * @return mixed
     */
    public function __get($name)
    {
        $allowNames = ['io'];

        if (in_array($name, $allowNames)) {
            return $this->$name;
        }

        return parent::__get($name);
    }
}
