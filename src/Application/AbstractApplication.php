<?php

namespace Apli\Application;

use Apli\Data\Structure;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * The Abstract Application Class.
 *
 * @property-read  Structure $config
 */
abstract class AbstractApplication implements LoggerAwareInterface
{
    /**
     * The application configuration object.
     *
     * @var    Structure
     */
    protected $config;

    /**
     * A logger object.
     *
     * @var    LoggerInterface
     */
    protected $logger;


    /**
     * Class constructor of Application.
     * @param   Structure $config An optional argument to provide a Structure object to be config.
     */
    public function __construct(Structure $config = null)
    {
        $this->config = $config instanceof Structure ? $config : new Structure;

        $this->init();
    }

    /**
     * Custom initialisation method.
     *
     * Called at the end of the AbstractApplication::__construct() method.
     * This is for developers to inject initialisation code for their application classes.
     *
     * @return  void
     */
    protected function init()
    {
    }

    /**
     * Method to close the application.
     *
     * @param   integer|string $message The exit code (optional; default is 0).
     *
     * @return  void
     */
    public function close($message = 0)
    {
        exit($message);
    }

    /**
     * Execute the application.
     *
     * @return  mixed
     */
    public function execute()
    {
        $this->prepareExecute();

        // @event onBeforeExecute

        // Perform application routines.
        $this->doExecute();

        // @event onAfterExecute

        return $this->postExecute();
    }

    /**
     * Prepare execute hook.
     *
     * @return  void
     */
    protected function prepareExecute()
    {
    }

    /**
     * Method to run the application routines. Most likely you will want to instantiate a controller
     * and execute it, or perform some sort of task directly.
     *
     * @return  void
     */
    abstract protected function doExecute();

    /**
     * Post execute hook.
     *
     * @return  mixed
     */
    protected function postExecute()
    {
    }

    /**
     * Returns a property of the object or the default value if the property is not set.
     *
     * @param   string $key The name of the property.
     * @param   mixed $default The default value (optional) if none is set.
     *
     * @return  mixed   The value of the configuration.
     */
    public function get($key, $default = null)
    {
        return $this->config->get($key, $default);
    }

    /**
     * Get the logger.
     *
     * @return  LoggerInterface
     */
    public function getLogger()
    {
        // If a logger hasn't been set, use NullLogger
        if (!($this->logger instanceof LoggerInterface)) {
            $this->logger = new NullLogger;
        }

        return $this->logger;
    }

    /**
     * Set the logger.
     *
     * @param   LoggerInterface $logger The logger.
     *
     * @return  AbstractApplication  Returns itself to support chaining.
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * Modifies a property of the object, creating it if it does not already exist.
     *
     * @param   string $key The name of the property.
     * @param   mixed $value The value of the property to set (optional).
     *
     * @return  mixed   Previous value of the property
     */
    public function set($key, $value = null)
    {
        $previous = $this->config->get($key);

        $this->config->set($key, $value);

        return $previous;
    }

    /**
     * Sets the configuration for the application.
     *
     * @param   Structure $config A structure object holding the configuration.
     *
     * @return  AbstractApplication  Returns itself to support chaining.
     */
    public function setConfiguration(Structure $config)
    {
        $this->config = $config;

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
        $allowNames = [
            'config',
        ];

        if (in_array($name, $allowNames)) {
            return $this->$name;
        }

        throw new \UnexpectedValueException('Property: ' . $name . ' not found in ' . get_called_class());
    }
}