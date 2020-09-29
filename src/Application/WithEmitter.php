<?php
/**
 *  Copyright (c) 2019 Danilo Andrade
 *
 *  This file is part of the apli project.
 *
 *  @project apli
 *  @file WithEmitter.php
 *  @author Danilo Andrade <danilo@webbingbrasil.com.br>
 *  @date 17/11/19 at 20:25
 */

/**
 * Created by PhpStorm.
 * User: Danilo
 * Date: 17/11/2019
 * Time: 20:25
 */

namespace Apli\Application;

use Apli\Http\Emitter\EmitterStack;

trait WithEmitter
{
    /**
     * Property server.
     *
     * @var EmitterStack
     */
    protected $emitter;

    /**
     * @return EmitterStack
     */
    public function getEmitter(): EmitterStack
    {
        if($this->emitter === null) {
            $this->emitter = new EmitterStack();
        }

        return $this->emitter;
    }
}
