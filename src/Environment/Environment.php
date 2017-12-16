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
     * Property sapi name.
     *
     * @var  string
     */
    protected $sapi = '';

    /**
     * @var Server
     */
    protected $server;

    /**
     * Environment constructor.
     *
     * @param array $server
     */
    public function __construct( array $server = [], $sapi = '' ) {
        $this->setSapiName( $sapi );
        $this->server = new Server( $server );
    }

    /**
     * Set sapi interface name
     *
     * @param $interface
     */
    public function setSapiName( $interface = '' ) {
        $this->sapi = $interface ?: php_sapi_name();
    }

    /**
     * @return Server
     */
    public function server() {
        return $this->server;
    }

    /**
     * Check if is running in a web environment
     * @return bool
     */
    public function isWeb() {
        return ! $this->isCli();
    }

    /**
     * Check if is running in a cli environment
     * @return bool
     */
    public function isCli() {
        return ( substr( $this->sapi, 0, 3 ) === 'cli' );
    }

    /**
     * Get PHP/HHVM version
     *
     * @return string
     */
    public function getPhpVersion() {
        if ( $this->isHHVM() ) {
            return constant( 'HHVM_VERSION' );
        }

        return constant( 'PHP_VERSION' );
    }

    /**
     * @return bool
     */
    public function isHHVM() {
        return defined( 'HHVM_VERSION' );
    }

    /**
     * isPHP
     *
     * @return  boolean
     */
    public function isPHP() {
        return ! $this->isHHVM();
    }
}
