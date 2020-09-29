<?php
/*
 *  Copyright (c) 2020 Danilo Andrade
 *
 *  This file is part of the apli project.
 *
 *  @project apli
 *  @file PubRec.php
 *  @author Danilo Andrade <danilo@webbingbrasil.com.br>
 *  @date 13/09/20 at 17:50
 */

namespace Apli\MQTT\Protocol\Reply;


class PubRec extends Reply
{
    function __toString()
    {
        return chr(0x62) . chr(0x02) . $this->parser->getPacketId();
    }
}
