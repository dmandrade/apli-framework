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
     * OS type constant
     * @var OsInterface
     */
    protected $isCli;

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
        $this->server   = $_SERVER;
        $this->isCli    = substr( php_sapi_name(), 0, 3 ) === 'cli';
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
        $this->kernel = strtoupper( $kernel );
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
        return ( $this->kernel === 'DARWIN' );
    }

    /**
     * Check if is running in a cli environment
     * @return bool
     */
    public function isCli() {
        return $this->isCli;
    }

    /**
     * Check if is running in a web environment
     * @return bool
     */
    public function isWeb() {
        return ! $this->isCli;
    }

}
