<?php
/**
 *  Copyright (c) 2019 Danilo Andrade
 *
 *  This file is part of the apli project.
 *
 *  @project apli
 *  @file WithEnvironment.php
 *  @author Danilo Andrade <danilo@webbingbrasil.com.br>
 *  @date 17/11/19 at 20:23
 */

/**
 * Created by PhpStorm.
 * User: Danilo
 * Date: 17/11/2019
 * Time: 20:23
 */

namespace Apli\Application;

use Apli\Environment\Environment;

trait WithEnvironment
{
    /**
     * The application environment object.
     *
     * @var Environment
     */
    protected $environment;


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
    public function setEnvironment(Environment $environment = null): self
    {
        $this->environment = $environment ?? new Environment();

        return $this;
    }
}
