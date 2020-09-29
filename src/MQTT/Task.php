<?php
/*
 *  Copyright (c) 2020 Danilo Andrade
 *
 *  This file is part of the apli project.
 *
 *  @project apli
 *  @file Task.php
 *  @author Danilo Andrade <danilo@webbingbrasil.com.br>
 *  @date 13/09/20 at 18:39
 */

namespace Apli\MQTT;


class Task
{

    public $fd = 0;
    public $topic;
    public $controller = 'common';
    public $action = 'default';
    public $param = '';
    public $body = '';
    public $verb = 'publish';

    const VERB_PUBLISH = 'publish';
    const VERB_SUBSCRIBE = 'subscribe';
    const VERB_ASYNC = 'async';
    const VERB_INTERNAL = 'internal';

    public function __construct($fd, $topic, $payload = '', $verb = 'publish')
    {
        $this->fd = $fd;
        $this->topic = $topic;
        $this->verb = $verb;
        $this->body = $payload;
        $this->resolve($topic);
    }

    /**
     * Mqtt Publish task
     * @param $fd
     * @param $topic
     * @param string $payload
     * @return static
     * @throws \Exception
     */
    public static function publish($fd, $topic, $payload = '')
    {
        return new static($fd, $topic, $payload, 'publish');
    }

    /**
     * Mqtt subscribe task
     * @param $fd
     * @param $topic
     * @return static
     * @throws \Exception
     */
    public static function subscribe($fd, $topic, $req_qos)
    {
        return new static($fd, $topic, $req_qos, 'subscribe');
    }

    /**
     * Redis subscribe task
     *
     * Can play like this: $redis->publish('supervisor', 'channel/play/100011'),
     * then the task will do something like mqtt publish
     *
     * @param $message
     * @return static
     */
    public static function async($message)
    {
        return new static(0, $message, '', 'async');
    }

    /**
     * internal job
     * @param $route
     * @param $param
     * @return static
     */
    public static function internal($route, $param = '')
    {
        return new static(0, $route, $param, 'internal');
    }

    private function resolve($topic)
    {
        if (preg_match('/(\w+)\/?(\w*)\/?(.*)/s', $topic, $routes)) {
            $this->controller = $routes[1];
            $this->action = isset($routes[2]) ? $routes[2] : 'default';
            $this->param = isset($routes[3]) ? $routes[3] : '';
        }
        //resolve async task from redis pub/sub, controller/action/param/payload
        if ($this->verb == 'async' && preg_match('/(\w+)\/(.*)/s', $this->param, $matches)) {
            $this->param = $matches[1];
            $this->body = $matches[2];
        }
    }

    public function __toString()
    {
        return '#Task# verb: ' . $this->verb . ' controller: ' . $this->controller . ' action: ' . $this->action . ' param: ' . json_encode($this->param) . ' payload: ' . json_encode($this->body) . PHP_EOL;
    }
}
