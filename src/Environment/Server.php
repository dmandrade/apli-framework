<?php
/**
 *  Copyright (c) 2018 Danilo Andrade
 *
 *  This file is part of the apli project.
 *
 * @project apli
 * @file Server.php
 * @author Danilo Andrade <danilo@webbingbrasil.com.br>
 * @date 27/08/18 at 10:27
 */

namespace Apli\Environment;

/**
 * Class Server.
 */
class Server
{
    /**
     * Property server data.
     *
     * @var array
     */
    protected $server = [];

    /**
     * @var Platform
     */
    protected $platform;

    /**
     * Server constructor.
     *
     * @param array $server
     */
    public function __construct(array $server = [])
    {
        $this->server = $server ?: $_SERVER;
    }

    /**
     * @return Platform
     */
    public function platform()
    {
        if (is_null($this->platform)) {
            $this->platform = new Platform();
        }

        return $this->platform;
    }

    /**
     * Get root directory.
     *
     * @return string
     */
    public function getRoot()
    {
        return dirname($this->getEntry());
    }

    /**
     * Get script entry.
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
     * Get current working directory.
     *
     * @return string
     */
    public function getWorkingDirectory()
    {
        return getcwd();
    }

    /**
     * Get a server param.
     *
     * @param      $key
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
     * Get server document root.
     *
     * @return mixed|null
     */
    public function getPublicRoot()
    {
        return $this->getParam('DOCUMENT_ROOT');
    }

    /**
     * Get server requested uri.
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
     * Get server host.
     *
     * @return mixed|null
     */
    public function getHost()
    {
        return $this->getParam('HTTP_HOST');
    }

    /**
     * Get server port.
     *
     * @return mixed|null
     */
    public function getPort()
    {
        return $this->getParam('SERVER_PORT');
    }

    /**
     * Get server schema.
     *
     * @return mixed|null
     */
    public function getScheme()
    {
        return $this->getParam('REQUEST_SCHEME');
    }
}
