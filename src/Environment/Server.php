<?php
/**
 *  Copyright (c) 2017 Danilo Andrade (http://daniloandrade.net)
 *
 *  This file is part of the Aplí Framework.
 *
 * @project Aplí Framework
 * @file Server.php
 * @author Danilo Andrade <danilo@daniloandrade.net>
 * @date 16/12/17 at 15:11
 * @copyright  Copyright (c) 2017 Danilo Andrade
 * @license    GNU Lesser General Public License version 3 or later.
 */

namespace Apli\Environment;

/**
 * Class Server
 *
 * @package Apli\Environment
 */
class Server
{

    /**
     * Property server data.
     *
     * @var  array
     */
    protected $server = [];

    /**
     * @var Platform
     */
    protected $os;

    /**
     * Server constructor.
     *
     * @param array $server
     */
    public function __construct(array $server = [])
    {
        $this->server = $server ?: $_SERVER;
        $this->os = new Platform();
    }

    /**
     * @return Platform
     */
    public function os()
    {
        return $this->os;
    }

    /**
     * Get root directory
     *
     * @return string
     */
    public function getRoot()
    {
        return dirname($this->getEntry());
    }

    /**
     * Get script entry
     *
     * @return bool|mixed|string
     */
    public function getEntry()
    {
        $wdir = $this->getWorkingDirectory();

        $file = $this->getParam('SCRIPT_FILENAME');

        if (strpos($file, $wdir) === 0) {
            $file = substr($file, strlen($wdir));
        }

        $file = trim($file, '.'.DIRECTORY_SEPARATOR);

        return $file;
    }

    /**
     * Get current working directory
     *
     * @return string
     */
    public function getWorkingDirectory()
    {
        return getcwd();
    }

    /**
     * Get a server param
     *
     * @param $key
     * @param null $default
     *
     * @return mixed|null
     */
    protected function getParam($key, $default = null)
    {
        if (isset($this->server[$key])) {
            return $this->server[$key];
        }

        return $default;
    }

    /**
     * Get server document root
     *
     * @return mixed|null
     */
    public function getPublicRoot()
    {
        return $this->getParam('DOCUMENT_ROOT');
    }

    /**
     * Get server requested uri
     *
     * @param bool $withParams
     *
     * @return mixed|null
     */
    public function getRequestUri($withParams = true)
    {
        if ($withParams) {
            return $this->getParam('REQUEST_URI');
        }

        return $this->getParam('PHP_SELF');
    }

    /**
     * Get server host
     *
     * @return mixed|null
     */
    public function getHost()
    {
        return $this->getParam('HTTP_HOST');
    }

    /**
     * Get server port
     *
     * @return mixed|null
     */
    public function getPort()
    {
        return $this->getParam('SERVER_PORT');
    }

    /**
     * Get server schema
     *
     * @return mixed|null
     */
    public function getScheme()
    {
        return $this->getParam('REQUEST_SCHEME');
    }
}
