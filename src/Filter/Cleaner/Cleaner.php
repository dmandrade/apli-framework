<?php
/**
 *  Copyright (c) 2018 Danilo Andrade
 *
 *  This file is part of the apli project.
 *
 *  @project apli
 *  @file Cleaner.php
 *  @author Danilo Andrade <danilo@webbingbrasil.com.br>
 *  @date 27/08/18 at 10:27
 */

namespace Apli\Filter\Cleaner;

/**
 * Interface FilterRuleInterface.
 */
interface Cleaner
{
    /**
     * Method to clean text by rule.
     *
     * @param string $source The source to be clean.
     *
     * @return mixed The cleaned value.
     */
    public function clean($source);
}
