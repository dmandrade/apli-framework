<?php
/**
 *  Copyright (c) 2018 Danilo Andrade
 *
 *  This file is part of the apli project.
 *
 * @project apli
 * @file InputFilter.php
 * @author Danilo Andrade <danilo@webbingbrasil.com.br>
 * @date 27/08/18 at 10:27
 */

namespace Apli\Filter;

use Apli\Filter\Cleaner\AlnumCleaner;
use Apli\Filter\Cleaner\ArrayCleaner;
use Apli\Filter\Cleaner\Base64Cleaner;
use Apli\Filter\Cleaner\BooleanCleaner;
use Apli\Filter\Cleaner\Cleaner;
use Apli\Filter\Cleaner\CmdCleaner;
use Apli\Filter\Cleaner\EmailCleaner;
use Apli\Filter\Cleaner\FloatCleaner;
use Apli\Filter\Cleaner\HtmlCleaner;
use Apli\Filter\Cleaner\IntegerCleaner;
use Apli\Filter\Cleaner\PathCleaner;
use Apli\Filter\Cleaner\StringCleaner;
use Apli\Filter\Cleaner\UintCleaner;
use Apli\Filter\Cleaner\UrlCleaner;
use Apli\Filter\Cleaner\UsernameCleaner;
use Apli\Filter\Cleaner\WordCleaner;

/**
 * Class Filter.
 */
class InputFilter implements \Serializable
{
    /**
     * Property handlers.
     *
     * @var string|callable[]
     */
    protected $handlers = [];

    /**
     * A list of native handlers for filter
     * @var array
     */
    protected $nativeHandlers = [
        'HTML' => HtmlCleaner::class,
        'INTEGER' => IntegerCleaner::class,
        'UINT' => UintCleaner::class,
        'FLOAT' => FloatCleaner::class,
        'BOOLEAN' => BooleanCleaner::class,
        'WORD' => WordCleaner::class,
        'ALNUM' => AlnumCleaner::class,
        'CMD' => CmdCleaner::class,
        'BASE64' => Base64Cleaner::class,
        'STRING' => StringCleaner::class,
        'ARRAY' => ArrayCleaner::class,
        'PATH' => PathCleaner::class,
        'USERNAME' => UsernameCleaner::class,
        'EMAIL' => EmailCleaner::class,
        'URL' => UrlCleaner::class,
    ];

    /**
     * Property unknownHandler.
     *
     * @var callable
     */
    protected $defaultHandler = null;

    /**
     * InputFilter constructor.
     */
    public function __construct()
    {
        $this->loadDefaultHandlers();
    }


    /**
     * Load default clean handlers.
     *
     * @return void
     */
    protected function loadDefaultHandlers()
    {
        foreach ($this->nativeHandlers as $name => $handler) {
            if (!$this->hasHandler($name)) {
                $this->setHandler($name, $handler);
            }
        }

        // Function to handle raw data
        if (!$this->hasHandler('RAW')) {
            $this->handlers['RAW'] = function ($source) {
                return $source;
            };
        }

        // Function to handler unknown clean
        $this->defaultHandler = function ($source) {
            /** @var HtmlCleaner $filter * */
            $filter = $this->getHandler('html');
            // Are we dealing with an array?
            if (is_array($source)) {
                foreach ($source as $key => $value) {
                    // Filter element for XSS and other 'bad' code etc.
                    if (is_string($value)) {
                        $source[$key] = $filter->remove($filter->decode($value));
                    }
                }

                return $source;
            } else {
                // Or a string?
                if (is_string($source) && !empty($source)) {
                    // Filter source for XSS and other 'bad' code etc.
                    return $filter->remove($filter->decode($source));
                } else {
                    // Not an array or string.. return the passed parameter
                    return $source;
                }
            }
        };
    }

    public function hasHandler($name)
    {
        return isset($this->handlers[strtoupper($name)]);
    }

    /**
     * Add a new clean handler
     *
     * @param $name
     * @param $handler
     * @return $this
     * @throws \ReflectionException
     */
    public function setHandler($name, $handler)
    {
        if ($this->isValidHandler($handler)) {
            $this->handlers[strtoupper($name)] = $handler;
        }

        return $this;
    }

    /**
     * Check if passed handler is valid
     *
     * @param $handler
     * @return bool
     * @throws \ReflectionException
     * @throws \InvalidArgumentException
     */
    private function isValidHandler($handler)
    {
        if (is_string($handler)) {
            $reflection = new \ReflectionClass($handler);
            if (!$reflection->implementsInterface(Cleaner::class)) {
                throw new \InvalidArgumentException($reflection->getName().' should implements Cleaner');
            }

            return true;
        }

        if (!($handler instanceof \Closure)) {
            throw new \InvalidArgumentException('The filter handler must be a class that implements the Cleaner or a Closure.');
        }

        return true;
    }

    /**
     * getHandlers.
     *
     * @param string $name
     *
     * @return \callable
     */
    public function getHandler($name)
    {
        return new $this->handlers[strtoupper($name)]();
    }

    /**
     * clean.
     *
     * @param string                 $source
     * @param string|callable|object $filter
     *
     * @return mixed
     */
    public function clean($source, $filter = 'string')
    {
        // Find handler to filter this text
        if ($filter instanceof \Closure) {
            return $filter($source);
        }

        $filter = strtoupper($filter);

        if (!empty($this->handlers[$filter]) && $this->handlers[$filter] instanceof Cleaner) {
            return $this->handlers[$filter]->clean($source);
        } elseif (!empty($this->handlers[$filter]) && is_callable($this->handlers[$filter])) {
            return $this->handlers[$filter]($source);
        }

        // Use default handler
        if (is_callable($this->defaultHandler)) {
            $defaultFilter = $this->defaultHandler;

            return $defaultFilter($source);
        }

        // No any filter matched, return source.
        return $source;
    }

    /**
     * setDefaultHandler.
     *
     * @param callable $defaultHandler
     *
     * @return static Return self to support chaining.
     */
    public function setDefaultHandler($defaultHandler)
    {
        $this->defaultHandler = $defaultHandler;

        return $this;
    }

    /**
     * Method to serialize the Filter.
     *
     * @return string The serialized Filter.
     */
    public function serialize()
    {
        $this->defaultHandler = null;
        $handlers = $this->handlers;

        foreach ($handlers as $name => $handler) {
            if (!is_string($handler)) {
                unset($handlers[$name]);
            }
        }

        // Serialize the options, data, and inputs.
        return serialize($handlers);
    }

    /**
     * Method to unserialize the Filter.
     *
     * @param string $input The serialized Filter.
     *
     * @return static The Filter object.
     */
    public function unserialize($input)
    {
        $handlers = unserialize($input);

        $this->handlers = $handlers;
        $this->loadDefaultHandlers();
    }
}
