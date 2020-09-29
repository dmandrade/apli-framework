<?php
/*
 *  Copyright (c) 2020 Danilo Andrade
 *
 *  This file is part of the apli project.
 *
 *  @project apli
 *  @file ConAck.php
 *  @author Danilo Andrade <danilo@webbingbrasil.com.br>
 *  @date 13/09/20 at 17:47
 */

namespace Apli\MQTT\Protocol\Reply;

class ConAck extends Reply
{
    const ACCEPT = 0x00;
    const REFUSE_PROTOCOL = 0x01;
    const REFUSE_IDENTIFIER = 0x02;
    const REFUSE_SERVER_UNAVAILABLE = 0x03;
    const REFUSE_AUTH_FAIL = 0x04;
    const REFUSE_NOT_AUTH = 0x05;

    private $flag = self::ACCEPT;

    function getFlag()
    {
        return $this->flag;
    }

    function setFlag($flag)
    {
        $this->flag = $flag;
    }

    function __toString()
    {
        return chr(0x20) . chr(0x02) . chr(0) . chr($this->flag);
    }
}
