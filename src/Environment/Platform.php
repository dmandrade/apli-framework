<?php
/**
 *  Copyright (c) 2017 Danilo Andrade (http://daniloandrade.net).
 *
 *  This file is part of the Aplí Framework.
 *
 * @project Aplí Framework
 * @file Platform.php
 *
 * @author Danilo Andrade <danilo@daniloandrade.net>
 * @date 16/12/17 at 15:12
 *
 * @copyright  Copyright (c) 2017 Danilo Andrade
 * @license    GNU Lesser General Public License version 3 or later.
 */

namespace Apli\Environment;

use Apli\Environment\OperatingSystems\MacOsx;

/**
 * Class Platform.
 */
class Platform
{
    /**
     * Canonical Kernel's name.
     *
     * @var string
     */
    protected $kernel;

    /**
     * OS Family.
     *
     * @var int
     */
    protected $family;

    /**
     * OS type constant.
     *
     * @var OperatingSystem
     */
    protected $operatingSystem = null;

    /**
     * @var SystemDetector
     */
    protected $systemDetector;

    /**
     * Platform constructor.
     */
    public function __construct()
    {
        $this->setKernelName(PHP_OS);
    }

    /**
     * Set kernel name.
     *
     * @param string $kernel
     *
     * @return $this
     */
    public function setKernelName($kernel)
    {
        $this->kernel = strtolower($kernel);

        return $this;
    }

    /**
     * Detect Operating system
     *
     * @return void
     */
    private function detectOperatingSystem()
    {
        if(is_null($this->systemDetector)) {
            $this->systemDetector = new SystemDetector();
        }

        $this->operatingSystem = $this->systemDetector->detect($this->kernel);
    }

    /**
     * Get operating system
     * @return OperatingSystem
     */
    public function operatingSystem()
    {
        if(is_null($this->operatingSystem)){
            $this->detectOperatingSystem();
        }

        return $this->operatingSystem;
    }

    /**
     * Get kernel name.
     *
     * @return string
     */
    public function getKernelName()
    {
        return $this->kernel;
    }

    /**
     * Get the Family name.
     *
     * @return string
     */
    public function getOsFamily()
    {
        return $this->operatingSystem()->getFamily();
    }

    /**
     * Get OS name.
     *
     * @return string
     */
    public function getOsName()
    {
        return $this->operatingSystem()->getName();
    }

    /**
     * Check if OS is unix.
     *
     * @return bool
     */
    public function isUnix()
    {
        return $this->operatingSystem()->getFamily() === SystemDetector::UNIX_FAMILY;
    }

    /**
     * Check if OS is unix.
     *
     * @return bool
     */
    public function isUnixOnWindows()
    {
        return $this->operatingSystem()->getFamily() === SystemDetector::UNIX_ON_WINDOWS_FAMILY;
    }

    /**
     * Check if OS is Windows.
     *
     * @return bool
     */
    public function isWindows()
    {
        return $this->operatingSystem()->getFamily() === SystemDetector::WINDOWS_FAMILY;
    }

    /**
     * Check if OS is OSX.
     *
     * @return bool
     */
    public function isOsx()
    {
        return $this->operatingSystem() instanceof MacOsx;
    }
}
