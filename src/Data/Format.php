<?php

namespace Apli\Data;

use Apli\Support\AbstractEnum;

/**
 * Class Format
 *
 * @method static Format JSON()
 * @method static Format INI()
 * @method static Format YAML()
 * @method static Format XML()
 * @method static Format PHP()
 *
 * @package Apli\Data
 */
class Format extends AbstractEnum implements FormatConverter
{
    const JSON = 'json';
    const INI = 'ini';
    const YAML = 'yaml';
    const XML = 'xml';
    const PHP = 'php';

    /**
     * Output object into a formatted string.
     *
     * @return string Formatted string.
     */
    public function output()
    {
        return "teste";
    }
}
