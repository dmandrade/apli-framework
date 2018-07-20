<?php

namespace Apli\Filter\Cleaner;

/**
 * Interface FilterRuleInterface.
 */
interface CleanerInterface
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
