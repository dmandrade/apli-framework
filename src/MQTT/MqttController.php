<?php
/*
 *  Copyright (c) 2020 Danilo Andrade
 *
 *  This file is part of the apli project.
 *
 *  @project apli
 *  @file MqttController.php
 *  @author Danilo Andrade <danilo@webbingbrasil.com.br>
 *  @date 13/09/20 at 18:48
 */

namespace Apli\MQTT;

use Apli\Core\Http\Request;
use ErrorException;
use Exception;
use Throwable;
use Swoole\Server as SwooleServer;

abstract class MqttController
{
    public $server;
    public $fd;
    public $topic;
    public $verb;
    public $redis;

    /**
     * BaseController constructor.
     * @param SwooleServer $server
     * @param $fd
     * @param $topic
     * @param string $verb
     */
    public function __construct(SwooleServer $server, $fd, $topic, $verb = 'publish')
    {
        $this->fd = $fd;
        $this->topic = $topic;
        $this->verb = $verb;
        //$this->redis = $server->redis;
        $this->server = $server;
        $this->init();
    }

    /**
     * Init this class.
     *
     * @return  void
     */
    protected function init()
    {
        // Override it if you need.
    }

    /**
     * @param Request $request
     * @param mixed ...$params
     * @return mixed
     * @throws Throwable
     */
    public function __invoke()
    {
        $this->prepareExecute();

        $this->postExecute($this->doExecute());
    }

    /**
     * A hook before main process executing.
     */
    protected function prepareExecute(): void
    {
    }

    /**
     * A hook after main process executing.
     *
     * @param null $result
     * @return mixed
     */
    protected function postExecute($result = null)
    {
        return $result;
    }

    /**
     * The main execution process.
     *
     * @return mixed
     */
    abstract protected function doExecute();

    /**
     * Broadcast publish
     * @param $fds
     * @param $topic
     * @param $content
     * @return bool;
     */
    public function publish($fds, $topic, $content, $qos = 0)
    {
        if (!is_array($fds)) $fds = array($fds);
        $msg = $this->buildBuffer($topic, $content, $qos);
        $result = 1;
        $offline = [$topic];
        while ($fds) {
            $fd = (int)array_pop($fds);
            if ($this->server->exist($fd)) {
                $result &= $this->server->send($fd, $msg) ? 1 : 0;
            } else {
                //$this->redis->srem('mqtt_sub_fds_set_#' . $topic, $fd);
            }
        }
        return !!$result;
    }

    private function buildBuffer($topic, $content, $qos = 0x00, $cmd = 0x30, $retain = 0)
    {
        $buffer = "";
        $buffer .= $topic;
        if ($qos > 0) $buffer .= chr(rand(0, 0xff)) . chr(rand(0, 0xff));
        $buffer .= $content;
        $head = " ";
        $head{0} = chr($cmd + ($qos * 2));
        $head .= $this->setMsgLength(strlen($buffer) + 2);
        $package = $head . chr(0) . $this->setMsgLength(strlen($topic)) . $buffer;
        return $package;
    }

    private function setMsgLength($len)
    {
        $string = "";
        do {
            $digit = $len % 128;
            $len = $len >> 7;
            if ($len > 0)
                $digit = ($digit | 0x80);
            $string .= chr($digit);
        } while ($len > 0);
        return $string;
    }

    public function subFds($key, $prefix = '')
    {
        $prefix = $prefix ?: 'mqtt_sub_fds_set_#';
        $res = [];
//        $res = $this->redis->smembers($prefix . $key);
//        if (!$res)  {
//            return [];
//        }
        return $res;
    }

    public function getClientInfo()
    {
        $res = ['u' => '', 'c' => ''];
//        $info = $this->redis->hget('mqtt_online_hash_client@fd', $this->fd);
//        if ($info) {
//            $res = @unserialize($info);
//        }
        return $res;
    }
}
