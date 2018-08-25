<?php
/**
 * Created by PhpStorm.
 * User: Danilo
 * Date: 15/07/2018
 * Time: 14:22.
 */

namespace Apli\Filter\Cleaner;

class FloatCleaner implements Cleaner
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
        // Only use the first floating point value
        preg_match('/-?[0-9]+(\.[0-9]+)?/', (string) $source, $matches);

        return isset($matches[0]) ? (float) $matches[0] : null;
    }
}
