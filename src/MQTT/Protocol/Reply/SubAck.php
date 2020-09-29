<?php
/*
 *  Copyright (c) 2020 Danilo Andrade
 *
 *  This file is part of the apli project.
 *
 *  @project apli
 *  @file SubAck.php
 *  @author Danilo Andrade <danilo@webbingbrasil.com.br>
 *  @date 13/09/20 at 17:51
 */

namespace Apli\MQTT\Protocol\Reply;


class SubAck extends Reply
{
    function __toString()
    {
        $payload = chr($this->parser->getRequestSubscribeQos());
        return chr(0x90) . ($payload === '' ? chr(0x02) : chr(0x02 + strlen($payload))) . $this->parser->getPacketId() . $payload;
    }
}
