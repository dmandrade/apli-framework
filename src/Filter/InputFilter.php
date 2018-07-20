<?php

namespace Apli\Filter;

use Apli\Filter\Cleaner\AlnumCleaner;
use Apli\Filter\Cleaner\ArrayCleaner;
use Apli\Filter\Cleaner\Base64Cleaner;
use Apli\Filter\Cleaner\BooleanCleaner;
use Apli\Filter\Cleaner\CleanerInterface;
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
 * Class Filter
 */
class InputFilter implements \Serializable
{

    /**
     * Property handlers.
     *
     * @var  CleanerInterface[]|callable[]
     */
    protected $handlers = [];

    /**
     * Property unknownHandler.
     *
     * @var  callable
     */
    protected $defaultHandler = null;

    /**
     * Property htmlCleaner.
     *
     * @var  HtmlCleaner
     */
    protected $htmlCleaner = null;

    /**
     * Class init.
     *
     * @param $htmlCleaner
     */
    public function __construct(HtmlCleaner $htmlCleaner = null)
    {
        $this->htmlCleaner = $htmlCleaner ?: new HtmlCleaner;

        $this->loadDefaultHandlers();
    }

    /**
     * loadDefaultHandlers
     *
     * @return  void
     */
    protected function loadDefaultHandlers()
    {
        $filter = $this->htmlCleaner;

        $this->addHandler('HTML', $this->htmlCleaner);
        $this->addHandler('INTEGER', new IntegerCleaner());
        $this->addHandler('UINT', new UintCleaner());
        $this->addHandler('FLOAT', new FloatCleaner());
        $this->addHandler('BOOLEAN', new BooleanCleaner());
        $this->addHandler('WORD', new WordCleaner());
        $this->addHandler('ALNUM', new AlnumCleaner());
        $this->addHandler('CMD', new CmdCleaner());
        $this->addHandler('BASE64', new Base64Cleaner());
        $this->addHandler('STRING', new StringCleaner($this->htmlCleaner));
        $this->addHandler('ARRAY', new ArrayCleaner());
        $this->addHandler('PATH', new PathCleaner());
        $this->addHandler('USERNAME', new UsernameCleaner());
        $this->addHandler('EMAIL', new EmailCleaner());
        $this->addHandler('URL', new UrlCleaner());

        // RAW
        $this->handlers['RAW'] = function ($source) {
            return $source;
        };

        // UNKNOWN
        $this->defaultHandler = function ($source) use ($filter) {
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

    /**
     * setHandlers
     *
     * @param   string $name
     * @param   CleanerInterface|\callable $handler
     *
     * @throws  \InvalidArgumentException
     * @return  static  Return self to support chaining.
     */
    public function addHandler($name, $handler)
    {
        if (is_object($handler) && !($handler instanceof CleanerInterface) && !($handler instanceof \Closure)) {
            throw new \InvalidArgumentException('Object filter handler should extends CleanerInterface or be a Closure.');
        }

        $this->handlers[strtoupper($name)] = $handler;

        return $this;
    }

    /**
     * clean
     *
     * @param string $source
     * @param string|callable|object $filter
     *
     * @return  mixed
     */
    public function clean($source, $filter = 'string')
    {
        // Find handler to filter this text
        if ($filter instanceof \Closure) {
            return $filter($source);
        }

        $filter = strtoupper($filter);

        if (!empty($this->handlers[$filter]) && $this->handlers[$filter] instanceof CleanerInterface) {
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
     * getHandlers
     *
     * @param string $name
     *
     * @return  \callable
     */
    public function getHandler($name)
    {
        return $this->handlers[strtoupper($name)];
    }

    /**
     * gethtmlCleaner
     *
     * @return  \Apli\Filter\Cleaner\HtmlCleaner
     */
    public function getHtmlCleaner()
    {
        return $this->htmlCleaner;
    }

    /**
     * sethtmlCleaner
     *
     * @param   \Apli\Filter\Cleaner\HtmlCleaner $htmlCleaner
     *
     * @return  static  Return self to support chaining.
     */
    public function setHtmlCleaner($htmlCleaner)
    {
        $this->htmlCleaner = $htmlCleaner;

        return $this;
    }

    /**
     * getDefaultHandler
     *
     * @return  callable
     */
    public function getDefaultHandler()
    {
        return $this->defaultHandler;
    }

    /**
     * setDefaultHandler
     *
     * @param   callable $defaultHandler
     *
     * @return  static  Return self to support chaining.
     */
    public function setDefaultHandler($defaultHandler)
    {
        $this->defaultHandler = $defaultHandler;

        return $this;
    }

    /**
     * Method to serialize the Filter.
     *
     * @return  string  The serialized Filter.
     */
    public function serialize()
    {
        $this->handlers = null;
        $this->defaultHandler = null;

        // Serialize the options, data, and inputs.
        return serialize($this->htmlCleaner);
    }

    /**
     * Method to unserialize the Filter.
     *
     * @param   string $input The serialized Filter.
     *
     * @return  static  The Filter object.
     */
    public function unserialize($input)
    {
        $htmlCleaner = unserialize($input);

        $this->htmlCleaner = $htmlCleaner;

        $this->loadDefaultHandlers();
    }
}

