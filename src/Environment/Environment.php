<?php
/**
 *  Copyright (c) 2018 Danilo Andrade
 *
 *  This file is part of the apli project.
 *
 * @project apli
 * @file Environment.php
 * @author Danilo Andrade <danilo@webbingbrasil.com.br>
 * @date 27/08/18 at 10:27
 */

namespace Apli\Environment;

/**
 * Class Environment.
 */
class Environment
{
    /**
     * Property sapi name.
     *
     * @var string
     */
    protected $sapi = '';

    /**
     * @var Server
     */
    protected $server;

    /**
     * Environment constructor.
     * @param string $sapi
     */
    public function __construct($sapi = '')
    {
        $this->setSapiName($sapi);
    }

    /**
     * Set sapi interface name.
     *
     * @param $interface
     */
    public function setSapiName($interface = '')
    {
        $this->sapi = $interface ?: php_sapi_name();
    }

    /**
     * @return Server
     */
    public function server()
    {
        if (is_null($this->server)) {
            $this->server = new Server();
        }

        return $this->server;
    }

    /**
     * Check if is running in a web environment.
     *
     * @return bool
     */
    public function isWeb()
    {
        return !$this->isCli();
    }

    /**
     * Check if is running in a cli environment.
     *
     * @return bool
     */
    public function isCli()
    {
        return substr($this->sapi, 0, 3) === 'cli';
    }

    /**
     * Get PHP/HHVM version.
     *
     * @return string
     */
    public function getPhpVersion()
    {
        if ($this->isHHVM()) {
            return constant('HHVM_VERSION');
        }

        return constant('PHP_VERSION');
    }

    /**
     * @return bool
     */
    public function isHHVM()
    {
        return defined('HHVM_VERSION');
    }

    /**
     * isPHP.
     *
     * @return bool
     */
    public function isPHP()
    {
        return !$this->isHHVM();
    }
}
