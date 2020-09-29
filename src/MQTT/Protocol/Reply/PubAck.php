<?php
/*
 *  Copyright (c) 2020 Danilo Andrade
 *
 *  This file is part of the apli project.
 *
 *  @project apli
 *  @file PubAck.php
 *  @author Danilo Andrade <danilo@webbingbrasil.com.br>
 *  @date 13/09/20 at 17:48
 */

namespace Apli\MQTT\Protocol\Reply;

class PubAck extends Reply
{

    function __toString()
    {
        if ($this->parser->getQos() == 1) {
            return chr(0x40) . chr(0x02) . $this->parser->getPacketId();
        } else if ($this->parser->getQos() == 2) {
            return chr(0x50) . chr(0x02) . $this->parser->getPacketId();
        } else {
            return '';
        }
    }
}

