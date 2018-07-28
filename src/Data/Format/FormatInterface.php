<?php
namespace Apli\Data\Format;

/**
 * Interface FormatInterface
 * @package Apli\Data\Format
 */
interface FormatInterface
{
    /**
     * Converts an object into a formatted string.
     *
     * @param object $struct  Data Source Object.
     * @param array  $options An array of options for the formatter.
     *
     * @return string Formatted string.
     */
    static public function structToString($struct, array $options = []);

    /**
     * Converts a formatted string into an object.
     *
     * @param string $data    Formatted string
     * @param array  $options An array of options for the formatter.
     *
     * @return object Data Object
     */
    static public function stringToStruct($data, array $options = []);
}
