<?php
/*
 *  Copyright (c) 2020 Danilo Andrade
 *
 *  This file is part of the apli project.
 *
 *  @project apli
 *  @file MQTT.php
 *  @author Danilo Andrade <danilo@webbingbrasil.com.br>
 *  @date 13/09/20 at 17:23
 */

namespace Apli\MQTT;

use Apli\MQTT\Protocol\Message;
use Apli\MQTT\Protocol\Reply\ConAck;
use Apli\MQTT\Protocol\Reply\PubComp;
use Apli\MQTT\Protocol\Reply\Reply;
use Swoole\Server as SwooleServer;

class MQTT
{
    /** @var EventInterface */
    private $event;
    /** @var CacheInterface */
    private $cache;

    /**
     * MQTT constructor.
     * @param EventInterface|null $event
     * @param CacheInterface|null $cache
     */
    public function __construct(EventInterface $event = null, CacheInterface $cache = null)
    {
        $this->event = $event ?? new Event();
        $this->cache = $cache ?? new TableCache();
    }


    function setCache(CacheInterface $cache): MQTT
    {
        $this->cache = $cache;
        return $this;
    }

    function setEvent(EventInterface $event): MQTT
    {
        $this->event = $event;
        return $this;
    }

    /**
     * @param SwooleServer $server
     */
    function attachServer($server)
    {
        $server->set([
            'open_mqtt_protocol' => TRUE,
            'worker_num' => 2,
            'task_worker_num' => 2,
            'task_ipc_mode' => 3,
            'heartbeat_check_interval' => 60,
            'heartbeat_idle_time' => 180,
        ]);

        $server->on('receive', function (SwooleServer $server, $fd, $from, $buffer) {
            go(function () use ($server, $fd, $buffer) {
                try {
                    $message = new Message($buffer);
                    if ($message->getCommand()) {
                        $reply = null;

                        if($message->getCommand() === Message::CONNECT) {
                            if (empty($this->event)) {
                                $reply = new ConAck($message);
                            } else {
                                $server->task(Task::internal('common/connect/' . $fd, $message->getConnectInfo()));
                                $reply = $this->event->onConnect($message, $fd);
                            }

                            $connctionInfo = $message->getConnectInfo();
                            $clientIdFlag = $connctionInfo['clientId'];
                            $protocolNameFlag = $connctionInfo['protocol'];
                            $protocolLevelFlag = $connctionInfo['version'];
                            $reservedFlag = $connctionInfo['reserved'];
                            $cleanSessionFlag = $connctionInfo['cleanSession'];
                            $willFlagFlag = $connctionInfo['willFlag'];
                            $willQosFlag = $connctionInfo['willQos'];
                            $willRetainFlag = $connctionInfo['willRetain'];
                            $userNameFlag = $connctionInfo['willRetain'];
                            $passwordFlag = $connctionInfo['willRetain'];
                            $keepAlive = $connctionInfo['keepAlive'];

                            if ($this->cache->get($clientIdFlag)) {
                                $reply->setFlag(ConAck::REFUSE_SERVER_UNAVAILABLE);
                            }

                            if ($protocolNameFlag !== 'MQTT') {
                                $reply->setFlag(ConAck::REFUSE_PROTOCOL);
                            }

                            if ($protocolLevelFlag !== 4) {
                                $reply->setFlag(ConAck::REFUSE_PROTOCOL);
                            }

                            if ($reservedFlag !== 0) {
                                $reply->setFlag(ConAck::REFUSE_PROTOCOL);
                            }

                            if ($reply->getFlag() === ConAck::ACCEPT) {
                                $this->cache->set($clientIdFlag, []);
                            }
                        }

                        if (!is_null($message->ack)) {
                            //$server->send($fd, $message->ack);
                        }

                        switch ($message->getCommand()) {
                            case Message::PUBLISH:
                                //$reply = $this->event->onPublish($message, $fd);
                                return $server->task(Task::publish($fd, $message->getTopic(), $message->getPayload()));
                            case Message::PUBACK:

                                break;
                            case Message::PUBREC:

                                break;
                            case Message::PUBREL:
                                $reply = new PubComp($message);
                                break;
                            case Message::PUBCOMP:

                                break;
                            case Message::SUBSCRIBE:
                                //$reply = $this->event->onSubscribe($message, $fd);
                                $server->task(Task::internal('common/redis/sadd', ['mqtt_sub_fds_set_#' . $message->getTopic(), $fd]));
                                $server->task(Task::internal('common/redis/sadd', ['mqtt_sub_topics_set_#' . $fd, $message->getTopic()]));
                                return $server->task(Task::subscribe($fd, $message->getTopic(), $message->getQos()));
                                //break;
                            case Message::SUBACK:

                                break;
                            case Message::UNSUBSCRIBE:
                                //$reply = $this->event->onUnsubscribe($message, $fd);
                                return $server->task(Task::internal('common/unsub/' . $fd, $message->getTopic()));
                                //break;
                            case Message::UNSUBACK:

                                break;
                            case Message::PINGREQ:
                                $reply = $this->event->onPingreq($message, $fd);
                                break;
                            case Message::PINGRESP:

                                break;
                            case Message::DISCONNECT:
                                //$reply = $this->event->onDisconnect($message, $fd);
                                return $server->task(Task::internal('common/close/' . $fd, $message->getConnectInfo()));
                        }

                        if ($reply instanceof Reply && !empty($reply->__toString())) {
                            $server->send($fd, $reply->__toString());
                        }

                    } else {
                        $server->close($fd);
                    }
                } catch (\Exception $e) {
                    var_dump($e->getMessage());
                    $server->close($fd);
                }
            });
        });
    }
}
