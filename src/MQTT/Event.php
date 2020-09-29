<?php
/*
 *  Copyright (c) 2020 Danilo Andrade
 *
 *  This file is part of the apli project.
 *
 *  @project apli
 *  @file Event.php
 *  @author Danilo Andrade <danilo@webbingbrasil.com.br>
 *  @date 13/09/20 at 17:25
 */

namespace Apli\MQTT;

use Apli\MQTT\Protocol\Message;
use Apli\MQTT\Protocol\Reply\ConAck;
use Apli\MQTT\Protocol\Reply\PingResp;
use Apli\MQTT\Protocol\Reply\PubAck;
use Apli\MQTT\Protocol\Reply\SubAck;
use Apli\MQTT\Protocol\Reply\UnSubAck;

class Event implements EventInterface
{
    /**
     * @param Message $message
     * @param int $fd
     * @return ConAck
     */
    function onConnect(Message $message, int $fd): ConAck
    {
        // TODO: implement event dispatch
        echo $message;
        return new ConAck($message);
    }

    /**
     * @param Message $message
     * @param int $fd
     * @return PubAck
     */
    function onPublish(Message $message, int $fd): PubAck
    {
        // TODO: implement event dispatch
        echo $message;
        return new PubAck($message);
    }

    /**
     * @param Message $message
     * @param int $fd
     * @return SubAck
     */
    public function onSubscribe(Message $message, int $fd): SubAck
    {
        // TODO: implement event dispatch
        echo $message;
        return new SubAck($message);
    }

    /**
     * @param Message $message
     * @param int $fd
     * @return UnSubAck
     */
    public function onUnsubscribe(Message $message, int $fd): UnSubAck
    {
        // TODO: implement event dispatch
        echo $message;
        return new UnSubAck($message);
    }

    /**
     * @param Message $message
     * @param int $fd
     * @return PingResp
     */
    public function onPingreq(Message $message, int $fd): PingResp
    {
        // TODO: implement event dispatch
        echo $message;
        return new PingResp($message);
    }

    public function onDisconnect(Message $message, int $fd)
    {
        // TODO: implement event dispatch
        echo $message;
    }
}
