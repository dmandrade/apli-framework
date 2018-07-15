<?php

namespace Apli\Data;

/**
 * Interface DataInterface
 * @package Apli\Data
 */
interface DataInterface
{
    /**
     * Bind the data
     *
     * @param $values
     * @param bool $replaceNulls
     * @return mixed
     */
    public function bind($values, $replaceNulls = false);

    /**
     * Check if data is empty
     *
     * @return mixed
     */
    public function isNull();

    /**
     * Check if data is not null
     *
     * @return mixed
     */
    public function notNull();

    /**
     * Dump all data as array
     *
     * @return  array
     */
    public function dump();

    /**
     * __get
     *
     * @param   string $name
     *
     * @return  mixed
     */
    public function __get($name);
}
