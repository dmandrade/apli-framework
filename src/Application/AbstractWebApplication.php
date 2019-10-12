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
use Apli\Http\Emitter\EmitterInterface;
use Apli\Http\Emitter\EmitterStack;
use Apli\Http\Emitter\SapiEmitter;
use Apli\Http\ServerRequestFactory;
use Apli\Uri\UriException;
use InvalidArgumentException;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class AbstractWebApplication.
 *
 * @property-read  Environment            $environment
 * @property-read  EmitterInterface       $emitter
 * @property-read  ServerRequestInterface $request
 */
abstract class AbstractWebApplication extends AbstractApplication
{
    /**
     * The application environment object.
     *
     * @var Environment
     */
    protected $environment;

    /**
     * Property server.
     *
     * @var EmitterInterface
     */
    protected $emitter;

    /**
     * @var ServerRequestInterface
     */
    protected $request;

    /**
     * AbstractWebApplication constructor.
     *
     * @param ServerRequestInterface|null $request
     * @param Map|null                    $config
     * @param Environment|null            $environment
     *
     * @throws UriException|InvalidArgumentException
     */
    public function __construct(
        ServerRequestInterface $request = null,
        Map $config = null,
        Environment $environment = null
    ) {
        $this->request = $request ?: ServerRequestFactory::fromGlobals(
            $_SERVER, $_GET, $_POST, $_COOKIE, $_FILES
        );
        $environment = $environment ?: new Environment();
        $emitter = new EmitterStack();
        $emitter->push(new SapiEmitter());

        $this->setEnvironment($environment);
        $this->setEmitter($emitter);

        // Call the constructor as late as possible (it runs `init()`).
        parent::__construct($config);

        $this->set('execution.datetime', gmdate('Y-m-d H:i:s'));
        $this->set('execution.timestamp', time());
    }

    /**
     * @return Environment
     */
    public function getEnvironment(): Environment
    {
        return $this->environment;
    }

    /**
     * @param Environment $environment
     *
     * @return $this
     */
    public function setEnvironment(Environment $environment): self
    {
        $this->environment = $environment;

        return $this;
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
     * @return EmitterInterface
     */
    public function getEmitter(): EmitterInterface
    {
        return $this->emitter;
    }

    /**
     * @param EmitterInterface $emitter
     *
     * @return $this
     */
    public function setEmitter(EmitterInterface $emitter): self
    {
        $this->emitter = $emitter;

        return $this;
    }

    /**
     * Execute the application.
     *
     * @return mixed|void
     */
    public function execute()
    {
        $this->prepareExecute();
        $response = $this->postExecute($this->doExecute());
        $this->emitter->emit($response);
    }
}
