<?php
/**
 *  Copyright (c) 2018 Danilo Andrade
 *
 *  This file is part of the apli project.
 *
 *  @project apli
 *  @file StringCleaner.php
 *  @author Danilo Andrade <danilo@webbingbrasil.com.br>
 *  @date 27/08/18 at 10:26
 */

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
     * Method to clean text by rule.
     *
     * @param string $source The source to be clean.
     *
     * @return mixed The cleaned value.
     */
    public function clean($source)
    {
        return (string)$this->getFilter()->clean($this->getFilter()->decode((string)$source));
    }

    /**
     * @return HtmlCleaner
     */
    public function getFilter()
    {
        if (is_null($this->filter)) {
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
}
