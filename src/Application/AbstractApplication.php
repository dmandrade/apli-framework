<?php
/**
 *  Copyright (c) 2018 Danilo Andrade
 *
 *  This file is part of the apli project.
 *
 *  @project apli
 *  @file AbstractApplication.php
 *  @author Danilo Andrade <danilo@webbingbrasil.com.br>
 *  @date 27/08/18 at 10:26
 */

namespace Apli\Application;

use Apli\Data\Map;
use Apli\Http\Server\RequestHandler;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * The Abstract Application Class.
 *
 * @property-read  Map $config
 */
abstract class AbstractApplication implements LoggerAwareInterface
{
    /**
     * The application configuration object.
     *
     * @var Data
     */
    protected $config;

    /**
     * A logger object.
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Class constructor of Application.
     * @param Map|null $config
     */
    public function __construct(Map $config = null)
    {
        $this->config = $config instanceof Map ? $config : new Map();

        $this->init();
    }

    /**
     * Custom initialisation method.
     *
     * Called at the end of the AbstractApplication::__construct() method.
     * This is for developers to inject initialisation code for their application classes.
     *
     * @return void
     */
    protected function init()
    {
    }

    /**
     * Method to close the application.
     *
     * @param int|string $message The exit code (optional; default is 0).
     *
     * @return void
     */
    public function close($message = 0)
    {
        exit($message);
    }

    /**
     * Execute the application.
     *
     * @return mixed
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
     * @return void
     */
    protected function prepareExecute()
    {
    }

    /**
     * Method to run the application routines. Most likely you will want to instantiate a controller
     * and execute it, or perform some sort of task directly.
     *
     * @return void
     */
    abstract protected function doExecute();

    /**
     * Post execute hook.
     *
     * @return mixed
     */
    protected function postExecute()
    {
    }

    /**
     * Returns a property of the object or the default value if the property is not set.
     *
     * @param string $key The name of the property.
     * @param mixed  $default The default value (optional) if none is set.
     *
     * @return mixed The value of the configuration.
     */
    public function get($key, $default = null)
    {
        return $this->config->get($key, $default);
    }

    /**
     * Get the logger.
     *
     * @return LoggerInterface
     */
    public function getLogger()
    {
        // If a logger hasn't been set, use NullLogger
        if (!($this->logger instanceof LoggerInterface)) {
            $this->logger = new NullLogger();
        }

        return $this->logger;
    }

    /**
     * Set the logger.
     *
     * @param LoggerInterface $logger The logger.
     *
     * @return AbstractApplication Returns itself to support chaining.
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * Modifies a property of the object, creating it if it does not already exist.
     *
     * @param string $key The name of the property.
     * @param mixed  $value The value of the property to set (optional).
     *
     * @return mixed Previous value of the property
     */
    public function set($key, $value = null)
    {
        $previous = $this->config->get($key, null);

        $this->config->put($key, $value);

        return $previous;
    }

    /**
     * Sets the configuration for the application.
     *
     * @param Map $config A structure object holding the configuration.
     *
     * @return AbstractApplication Returns itself to support chaining.
     */
    public function setConfiguration(Map $config)
    {
        $this->config = $config;

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
        $allowNames = [
            'config',
        ];

        if (in_array($name, $allowNames)) {
            return $this->$name;
        }

        throw new \UnexpectedValueException('Property: '.$name.' not found in '.get_called_class());
    }
}
