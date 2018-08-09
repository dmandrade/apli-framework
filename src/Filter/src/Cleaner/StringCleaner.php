<?php
/**
 * Created by PhpStorm.
 * User: Danilo
 * Date: 15/07/2018
 * Time: 14:22.
 */

namespace Apli\Filter\Cleaner;

class StringCleaner implements CleanerInterface
{
    private $filter;

    /**
     * StringCleaner constructor.
     */
    public function __construct(HtmlCleaner $htmlCleaner)
    {
        $this->filter = $htmlCleaner;
    }

    /**
     * Method to clean text by rule.
     *
     * @param string $source The source to be clean.
     *
     * @return mixed The cleaned value.
     */
    public function clean($source)
    {
        return (string) $this->filter->clean($this->filter->decode((string) $source));
    }
}
