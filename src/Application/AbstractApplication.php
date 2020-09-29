<?php
/**
 *  Copyright (c) 2018 Danilo Andrade.
 *
 *  This file is part of the apli project.
 *
 * @project apli
 * @file AbstractApplication.php
 *
 * @author Danilo Andrade <danilo@webbingbrasil.com.br>
 * @date 27/08/18 at 10:26
 */

namespace Apli\Application;

use Apli\Data\Map;
use Apli\Support\Str;
use InvalidArgumentException;
use OutOfBoundsException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Throwable;
use UnexpectedValueException;
use function in_array;

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
     * @var array
     */
    protected $allowGetProperties = [
        'config',
        'logger'
    ];

    /**
     * Class constructor of Application.
     *
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
    protected function init(): void
    {
    }

    /**
     * Method to close the application.
     *
     * @param int|string $message The exit code (optional; default is 0).
     *
     * @return void
     */
    public function close($message = 0): void
    {
        exit($message);
    }

    /**
     * Execute the application.
     *
     * @return void
     */
    public function execute(): void
    {
        try {
            $this->prepareExecute();
            $this->postExecute($this->doExecute());
        } catch (Throwable $e) {
            $this->handleException($e);
        }
    }

    /**
     * @param Throwable $e
     */
    public function handleException(Throwable $e): void
    {
        print('<pre>' . $e->getMessage() . '<br/>' . implode('<br/>', $this->formatCallStack($e)));
    }

    /**
     * @param $e
     * @return array
     */
    protected function formatCallStack($e): array
    {
        $stack = $e->getTrace();

        foreach ($stack as $i => $call) {
            $stack[$i] = (@$call['file'] == '' ? 'lambda : ' : @$call['file'] . ' (' . $call['line'] . ') : ') .
                (@$call['class'] == '' ? '' : $call['class'] . '->') . $call['function'];
        }

        return $stack;
    }

    /**
     * Prepare execute hook.
     *
     * @return void
     */
    protected function prepareExecute(): void
    {
    }

    /**
     * Method to run the application routines. Most likely you will want to instantiate a controller
     * and execute it, or perform some sort of task directly.
     *
     * @return mixed
     */
    abstract protected function doExecute();

    /**
     * Post execute hook.
     * @param $response
     * @return void
     */
    protected function postExecute($response): void
    {
    }

    /**
     * Returns a property of the object or the default value if the property is not set.
     *
     * @param string $key     The name of the property.
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
    public function getLogger(): LoggerInterface
    {
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
    public function setLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * Modifies a property of the object, creating it if it does not already exist.
     *
     * @param      $key
     * @param null $value
     * @return mixed the previous value
     * @throws InvalidArgumentException
     */
    public function set($key, $value = null)
    {
        $previous = $this->config->get($key);

        $this->config->put($key, $value);

        return $previous;
    }

    /**
     * Sets the configuration for the application.
     *
     * @param Map $config A structure object holding the configuration.
     *
     * @return AbstractApplication
     */
    public function setConfiguration(Map $config): self
    {
        $this->config = $config;

        return $this;
    }


    /**
     * Determine if a get method exists for an property.
     *
     * @param  string  $property
     * @return bool
     */
    public function hasGetMethod($property): bool
    {
        return method_exists($this, 'get'.Str::studly($property));
    }

    /**
     * is utilized for reading data from inaccessible members.
     *
     * @param   $property string
     *
     * @return mixed
     */
    public function __get($property)
    {
        if ($this->__isset($property)) {
            if($this->hasGetMethod($property)) {
                return $this->{'get'.Str::studly($property)}();
            }
            return $this->$property;
        }

        throw new UnexpectedValueException('Property: '.$property.' not found in '.static::class);
    }

    /**
     * Prevent overwrite properties
     *
     * @param $property
     * @param $value
     */
    public function __set($property, $value)
    {
        throw new UnexpectedValueException('Not allowed to overwrite '.$property.' in '.static::class);
    }

    /**
     * @param $property
     * @return bool
     */
    public function __isset($property)
    {
        return in_array($property, $this->allowGetProperties, false);
    }
}
