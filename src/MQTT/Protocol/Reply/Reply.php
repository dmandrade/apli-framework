<?php
/*
 *  Copyright (c) 2020 Danilo Andrade
 *
 *  This file is part of the apli project.
 *
 *  @project apli
 *  @file Reply.php
 *  @author Danilo Andrade <danilo@webbingbrasil.com.br>
 *  @date 13/09/20 at 17:51
 */

namespace Apli\MQTT\Protocol\Reply;

use Apli\MQTT\Protocol\Message;

class Reply
{
    protected $parser;

    function __construct(?Message $bufferParser = null)
    {
        $this->parser = $bufferParser;
    }
}
