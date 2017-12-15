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

use Apli\Environment\Detector\AbstractOs;
use Apli\Environment\Detector\MacOsx;
use Apli\Environment\Detector\OsDetector;
use Apli\Environment\Detector\OsInterface;

/**
 * Class Environment
 * @package Apli\Environment
 */
class Environment {


    /**
     * Canonical Kernel's name
     * @var string
     */
    protected $kernel;

    /**
     * OS Family
     * @var int
     */
    protected $family;

    /**
     * OS type constant
     * @var OsInterface
     */
    protected $os;

    /**
     * Property server data.
     *
     * @var  array
     */
    protected $server = [];

    /**
     * Property sapi name.
     *
     * @var  string
     */
    protected $sapi = '';

    /**
     * Environment constructor.
     *
     * @param array $server
     */
    public function __construct(array $server = [], $sapi = '') {
        $this->server   = $server ? : $_SERVER;
        $this->setSapiName($sapi);
        $this->detector = new OsDetector();
        $this->setKernelName( PHP_OS );
    }

    private function detectOs() {
        $this->os = $this->detector->detectOs( $this->kernel );
    }

    /**
     * Set uname
     *
     * @param $uname
     *
     * @return $this
     */
    public function setKernelName( $kernel ) {
        $this->os     = null;
        $this->kernel = strtolower( $kernel );
        $this->detectOs();

        return $this;
    }

    /**
     * Get uname
     *
     * @return string
     */
    public function getKernelName() {
        return $this->kernel;
    }

    /**
     * Get the Family constant
     *
     * @return int
     */
    public function getOsFamily() {
        return $this->os->getFamily();
    }

    /**
     * Get OS name
     *
     * @return  string
     */
    public function getOsName() {
        return $this->os->getName();
    }

    /**
     * Check if OS is unix
     * @return bool
     */
    public function isUnix() {
        return ( $this->os->getFamily() === OsDetector::UNIX_FAMILY );
    }

    /**
     * Check if OS is unix
     * @return bool
     */
    public function isUnixOnWindows() {
        return ( $this->os->getFamily() === OsDetector::UNIX_ON_WINDOWS_FAMILY );
    }

    /**
     * Check if OS is Windows
     *
     * @return  bool
     */
    public function isWindows() {
        return ( $this->os->getFamily() === OsDetector::WINDOWS_FAMILY );
    }

    /**
     * If the Operating System is OSX
     * @return bool
     */
    public function isOsx() {
        return $this->os instanceof MacOsx;
    }

    /**
     * Set sapi interface name
     *
     * @param $interface
     */
    public function setSapiName($interface = '') {
        $this->sapi = $interface ? : php_sapi_name();
    }

    /**
     * Check if is running in a cli environment
     * @return bool
     */
    public function isCli() {
        return (substr( $this->sapi, 0, 3 ) === 'cli');
    }

    /**
     * Check if is running in a web environment
     * @return bool
     */
    public function isWeb() {
        return ! $this->isCli();
    }

    /**
     * Get PHP/HHVM version
     *
     * @return string
     */
    public function getPhpVersion() {
        if ( $this->isHHVM() ) {
            return HHVM_VERSION;
        }

        return PHP_VERSION;
    }

    /**
     * @return bool
     */
    public function isHHVM()
    {
        return defined('HHVM_VERSION');
    }
    /**
     * isPHP
     *
     * @return  boolean
     */
    public function isPHP()
    {
        return !$this->isHHVM();
    }

    /**
     * Get root directory
     *
     * @return string
     */
    public function getRoot() {
        return dirname( $this->getEntry() );
    }

    /**
     * Get script entry
     *
     * @return bool|mixed|string
     */
    public function getEntry() {
        $wdir = $this->getWorkingDirectory();

        $file = $this->getServerParam( 'SCRIPT_FILENAME' );

        if ( strpos( $file, $wdir ) === 0 ) {
            $file = substr( $file, strlen( $wdir ) );
        }

        $file = trim( $file, '.' . DIRECTORY_SEPARATOR );

        if ( $this->isCli() ) {
            $file = $wdir . DIRECTORY_SEPARATOR . $file;
        }

        return $file;
    }

    /**
     * Get current working directory
     *
     * @return string
     */
    public function getWorkingDirectory() {
        return getcwd();
    }

    /**
     * Get server document root
     *
     * @return mixed|null
     */
    public function getServerPublicRoot() {
        return $this->getServerParam( 'DOCUMENT_ROOT' );
    }

    /**
     * Get server requested uri
     *
     * @param bool $withParams
     *
     * @return mixed|null
     */
    public function getRequestUri( $withParams = true ) {
        if ( $withParams ) {
            return $this->getServerParam( 'REQUEST_URI' );
        }

        return $this->getServerParam( 'PHP_SELF' );
    }

    /**
     * Get server host
     *
     * @return mixed|null
     */
    public function getHost() {
        return $this->getServerParam( 'HTTP_HOST' );
    }

    /**
     * Get server port
     *
     * @return mixed|null
     */
    public function getPort() {
        return $this->getServerParam( 'SERVER_PORT' );
    }

    /**
     * Get server schema
     *
     * @return mixed|null
     */
    public function getScheme() {
        return $this->getServerParam( 'REQUEST_SCHEME' );
    }

    /**
     * Get a server param
     *
     * @param $key
     * @param null $default
     *
     * @return mixed|null
     */
    protected function getServerParam( $key, $default = null ) {
        if ( isset( $this->server[ $key ] ) ) {
            return $this->server[ $key ];
        }

        return $default;
    }
}
