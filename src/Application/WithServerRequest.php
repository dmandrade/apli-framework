<?php
/**
 *  Copyright (c) 2019 Danilo Andrade
 *
 *  This file is part of the apli project.
 *
 *  @project apli
 *  @file WithServerRequestCreator.php
 *  @author Danilo Andrade <danilo@webbingbrasil.com.br>
 *  @date 17/11/19 at 20:28
 */

/**
 * Created by PhpStorm.
 * User: Danilo
 * Date: 17/11/2019
 * Time: 20:25
 */

namespace Apli\Application;

use Apli\Http\HttpFactory;
use Apli\Http\HttpFactoryInterface;
use Apli\Http\ServerRequestCreator;
use Apli\Http\ServerRequestCreatorInterface;

trait WithServerRequest
{
    /**
     * @var HttpFactory
     */
    protected $httpFactory;
    /**
     * @var ServerRequestCreatorInterface
     */
    protected $serverRequestCreator;

    /**
     * @param HttpFactoryInterface|null $httpFactory
     */
    public function createServerRequestFrom(HttpFactoryInterface $httpFactory = null): void
    {
        $this->httpFactory = $httpFactory ?? new HttpFactory();
        $this->serverRequestCreator = new ServerRequestCreator(
            $this->httpFactory,
            $this->httpFactory,
            $this->httpFactory,
            $this->httpFactory
        );
    }
}
