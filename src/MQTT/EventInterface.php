<?php
/*
 *  Copyright (c) 2020 Danilo Andrade
 *
 *  This file is part of the apli project.
 *
 *  @project apli
 *  @file EventInterface.php
 *  @author Danilo Andrade <danilo@webbingbrasil.com.br>
 *  @date 13/09/20 at 17:26
 */

namespace Apli\MQTT;

use Apli\MQTT\Protocol\Message;
use Apli\MQTT\Protocol\Reply\ConAck;
use Apli\MQTT\Protocol\Reply\PingResp;
use Apli\MQTT\Protocol\Reply\PubAck;
use Apli\MQTT\Protocol\Reply\SubAck;
use Apli\MQTT\Protocol\Reply\UnSubAck;

interface EventInterface
{
    public function onConnect(Message $message, int $fd): ConAck;

    public function onPublish(Message $message, int $fd): PubAck;

    public function onSubscribe(Message $message, int $fd): SubAck;

    public function onUnsubscribe(Message $message, int $fd): UnSubAck;

    public function onPingreq(Message $message, int $fd): PingResp;

    public function onDisconnect(Message $message, int $fd);
}
