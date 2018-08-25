<?php
/**
 * Created by PhpStorm.
 * User: Danilo
 * Date: 15/07/2018
 * Time: 14:22.
 */

namespace Apli\Filter\Cleaner;

class StringCleaner implements Cleaner
{
    private $filter;

    /**
     * @return HtmlCleaner
     */
    public function getFilter()
    {
        if(is_null($this->filter)) {
            $this->filter = new HtmlCleaner();
        }

        return $this->filter;
    }

    /**
     * @param HtmlCleaner $filter
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;
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
        return (string) $this->getFilter()->clean($this->getFilter()->decode((string) $source));
    }
}
