<?php
/**
 * Created by PhpStorm.
 * User: Danilo
 * Date: 15/07/2018
 * Time: 14:22.
 */

namespace Apli\Filter\Cleaner;

class CmdCleaner implements CleanerInterface
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
        $result = (string) preg_replace('/[^A-Z0-9_\.-]/i', '', $source);

        return ltrim($result, '.');
    }
}
