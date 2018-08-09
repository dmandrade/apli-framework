<?php
namespace Apli\Data;

/**
 * Interface FormatInterface
 * @package Apli\Data\Format
 */
interface FormatConverter
{
    /**
     * Output object into a formatted string.
     *
     * @return string Formatted string.
     */
    function output();

    /**
     * @return mixed
     */
    function __toString();
}
