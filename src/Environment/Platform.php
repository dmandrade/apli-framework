<?php
/**
 *  Copyright (c) 2017 Danilo Andrade (http://daniloandrade.net)
 *
 *  This file is part of the Aplí Framework.
 *
 * @project Aplí Framework
 * @file Platform.php
 * @author Danilo Andrade <danilo@daniloandrade.net>
 * @date 16/12/17 at 15:12
 * @copyright  Copyright (c) 2017 Danilo Andrade
 * @license    GNU Lesser General Public License version 3 or later.
 */

namespace Apli\Environment;

use Apli\Environment\Detector\MacOsx;
use Apli\Environment\Detector\OsDetector;
use Apli\Environment\Detector\OsInterface;

/**
 * Class Platform
 * @package Apli\Environment
 */
class Platform {

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
     * @var OsDetector
     */
    protected $detector;

    /**
     * Platform constructor.
     */
    public function __construct() {
        $this->detector = new OsDetector();
        $this->setKernelName( PHP_OS );
    }

    /**
     * Set kernel name
     *
     * @param string $kernel
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
     * Set detected os
     *
     * @return void
     */
    private function detectOs() {
        $this->os = $this->detector->detectOs( $this->kernel );
    }

    /**
     * Get kernel name
     *
     * @return string
     */
    public function getKernelName() {
        return $this->kernel;
    }

    /**
     * Get the Family name
     *
     * @return string
     */
    public function getOsFamily() {
        return $this->os->getFamily();
    }

    /**
     * Get OS name
     *
     * @return string
     */
    public function getOsName() {
        return $this->os->getName();
    }

    /**
     * Check if OS is unix
     *
     * @return bool
     */
    public function isUnix() {
        return ( $this->os->getFamily() === OsDetector::UNIX_FAMILY );
    }

    /**
     * Check if OS is unix
     *
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
     * Check if OS is OSX
     *
     * @return bool
     */
    public function isOsx() {
        return $this->os instanceof MacOsx;
    }
}
