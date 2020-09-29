<?php
/**
 *  Copyright (c) 2018 Danilo Andrade.
 *
 *  This file is part of the apli project.
 *
 * @project apli
 * @file AbstractWebApplication.php
 *
 * @author Danilo Andrade <danilo@webbingbrasil.com.br>
 * @date 04/09/18 at 10:43
 */

/**
 * Created by PhpStorm.
 * User: Danilo
 * Date: 04/09/2018
 * Time: 10:43.
 */

namespace Apli\Application;

use Apli\Data\Map;
use Apli\Environment\Environment;
use Apli\Http\Emitter\SapiEmitter;
use Apli\Http\HttpFactoryInterface;
use Apli\Uri\UriException;
use InvalidArgumentException;

/**
 * Class AbstractWebApplication
 * @package Apli\Application
 */
abstract class AbstractWebApplication extends AbstractApplication
{
    use WithEnvironment, WithEmitter, WithServerRequest;

    /**
     * AbstractWebApplication constructor.
     *
     * @param HttpFactoryInterface|null $httpFactory
     * @param Map|null             $config
     * @param Environment|null     $environment
     *
     * @throws UriException|InvalidArgumentException
     */
    public function __construct(
        HttpFactoryInterface $httpFactory = null,
        Map $config = null,
        Environment $environment = null
    )
    {
        $this->createServerRequestFrom($httpFactory);
        $this->setEnvironment($environment);

        // Call the constructor as late as possible (it runs `init()`).
        parent::__construct($config);

        $this->set('execution.datetime', gmdate('Y-m-d H:i:s'));
        $this->set('execution.timestamp', time());
    }

    /**
     * Method to check to see if headers have already been sent.
     * We wrap headers_sent() function with this method for testing reason.
     *
     * @return bool
     *
     * @see     headers_sent()
     */
    public function checkHeadersSent(): bool
    {
        return headers_sent();
    }

    /**
     * Post execute hook.
     * @param $response
     * @return void
     */
    protected function postExecute($response): void
    {
        $this->getEmitter()->emit($response);
    }
}
