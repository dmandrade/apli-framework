<?php
/**
 * Created by PhpStorm.
 * User: Danilo
 * Date: 09/07/2018
 * Time: 15:15
 */

namespace Apli\Application;

use Apli\Data\Structure;
use Apli\IO\Cli\IO;
use Apli\IO\Cli\IOInterface;

/**
 * Simple class for a Apli command line application.
 *
 * @property-read  IOInterface $io
 */
abstract class AbstractCliApplication extends AbstractApplication
{
    /**
     * The CLI In/Out object.
     *
     * @var    IOInterface
     */
    protected $io = null;

    /**
     * Class constructor.
     *
     * @param   IOInterface $io An optional argument to provide dependency injection for the application's
     *                                IO object.
     * @param   Structure $config An optional argument to provide dependency injection for the application's
     *                                config object.  If the argument is a Structure object that object will become
     *                                the application's config object, otherwise a default config object is created.
     */
    public function __construct(IOInterface $io = null, Structure $config = null)
    {
        // Close the application if we are not executed from the command line.
        if (!defined('STDOUT') || !defined('STDIN') || !isset($_SERVER['argv'])) {
            $this->close();
        }

        $this->io = $io instanceof IOInterface ? $io : new IO;
        $this->config = $config instanceof Structure ? $config : new Structure;

        $this->init();

        // Set the execution datetime and timestamp;
        $this->set('execution.datetime', gmdate('Y-m-d H:i:s'));
        $this->set('execution.timestamp', time());

        // Set the current directory.
        $this->set('cwd', getcwd());
    }

    /**
     * Write a string to standard output.
     *
     * @param   string $text The text to display.
     * @param   boolean $nl True (default) to append a new line at the end of the output string.
     *
     * @return  AbstractCliApplication  Instance of $this to allow chaining.
     */
    public function out($text = '', $nl = true)
    {
        $this->io->out($text, $nl);

        return $this;
    }

    /**
     * Get a value from standard input.
     *
     * @return  string  The input string from standard input.
     */
    public function in()
    {
        return $this->io->in();
    }

    /**
     * Get the IO object.
     *
     * @return  IOInterface
     */
    public function getIO()
    {
        return $this->io;
    }

    /**
     * Set the IO object.
     *
     * @param   IOInterface $io The IO object.
     *
     * @return  AbstractCliApplication  Return self to support chaining.
     */
    public function setIO($io)
    {
        $this->io = $io;

        return $this;
    }

    /**
     * is utilized for reading data from inaccessible members.
     *
     * @param   $name  string
     *
     * @return  mixed
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