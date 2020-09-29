<?php
/*
 *  Copyright (c) 2020 Danilo Andrade
 *
 *  This file is part of the apli project.
 *
 *  @project apli
 *  @file PingResp.php
 *  @author Danilo Andrade <danilo@webbingbrasil.com.br>
 *  @date 13/09/20 at 17:48
 */

namespace Apli\MQTT\Protocol\Reply;

class PingResp extends Reply
{
    function __toString()
    {
        return chr(0xD0) . chr(0);
    }
}
