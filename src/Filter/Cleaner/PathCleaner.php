<?php
/**
 *  Copyright (c) 2018 Danilo Andrade.
 *
 *  This file is part of the apli project.
 *
 * @project apli
 * @file PathCleaner.php
 *
 * @author Danilo Andrade <danilo@webbingbrasil.com.br>
 * @date 27/08/18 at 10:27
 */

/**
 * Created by PhpStorm.
 * User: Danilo
 * Date: 15/07/2018
 * Time: 14:22.
 */

namespace Apli\Filter\Cleaner;

class PathCleaner implements Cleaner
{
    /**
     * Method to clean text by rule.
     *
     * @param string $source The source to be clean.
     *
     * @return mixed The cleaned value.
     */
    public function clean($source)
    {
        $pattern = '/^[A-Za-z0-9_-]+[A-Za-z0-9_\.-]*([\\\\\/][A-Za-z0-9_-]+[A-Za-z0-9_\.-]*)*$/';
        preg_match($pattern, (string) $source, $matches);

        return isset($matches[0]) ? (string) $matches[0] : null;
    }
}
