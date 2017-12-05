<?php
/**
 *  Copyright (c) 2017 Danilo Andrade (http://daniloandrade.net)
 *
 *  This file is part of the Aplí Framework.
 *
 * @project Aplí Framework
 * @file Environment.php
 * @author Danilo Andrade <danilo@daniloandrade.net>
 * @date 05/12/17 at 10:56
 * @copyright  Copyright (c) 2017 Danilo Andrade
 * @license    GNU Lesser General Public License version 3 or later.
 */

namespace Apli\Environment;

/**
 * Class Environment
 * @package Apli\Environment
 */
class Environment {

    /**
     * Property os.
     *
     * @var string
     */
    protected $os;

    /**
     * Property uname.
     *
     * @var  string
     */
    protected $uname = PHP_OS;

    /**
     * Property server data.
     *
     * @var  array
     */
    protected $server = [];

    /**
     * Environment constructor.
     *
     * @param array $server
     */
    public function __construct() {
        $this->server = $_SERVER;
    }


    /**
     * Get uname
     *
     * @return string
     */
    public function getUname() {
        return $this->uname;
    }

    /**
     * Set uname
     *
     * @param $uname
     *
     * @return $this
     */
    public function setUname( $uname ) {
        $this->uname = $uname;

        return $this;
    }

    /**
     * Check if OS is unix
     * @return bool
     */
    public function isUnix() {
        $unames = [
            'CYG',
            'DAR',
            'FRE',
            'HP-',
            'IRI',
            'LIN',
            'NET',
            'OPE',
            'SUN',
            'UNI'
        ];

        return in_array( $this->getOS(), $unames );
    }

    /**
     * Get OS name
     *
     * @return  string
     */
    public function getOS() {
        if ( ! $this->os ) {
            // Detect the native operating system type.
            $this->os = strtoupper( substr( $this->uname, 0, 3 ) );
        }

        return $this->os;
    }

    /**
     * Set OS name
     *
     * @param $os
     *
     * @return $this
     */
    public function setOS( $os ) {
        $this->os = $os;

        return $this;
    }

    /**
     * Check if OS is linux
     * @return bool
     */
    public function isLinux() {
        return $this->getOS() === 'LIN';
    }

    /**
     * Check if OS is Windows
     *
     * @return  bool
     */
    public function isWin() {
        return $this->getOS() === 'WIN';
    }

}
