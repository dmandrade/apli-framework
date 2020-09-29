<?php
/*
 *  Copyright (c) 2020 Danilo Andrade
 *
 *  This file is part of the apli project.
 *
 *  @project apli
 *  @file Message.php
 *  @author Danilo Andrade <danilo@webbingbrasil.com.br>
 *  @date 13/09/20 at 17:28
 */

namespace Apli\MQTT\Protocol;

use Exception;

class Message
{
    /**
     * @var int flag buffer split by fixed length
     */
    const FL_FIXED = 0;
    /**
     * @var int flag buffer split by follow buffer given
     */
    const FL_FOLLOW = 1;

    const CONNECT = 1;
    const CONNACK = 2;
    const PUBLISH = 3;
    const PUBACK = 4;
    const PUBREC = 5;
    const PUBREL = 6;
    const PUBCOMP = 7;
    const SUBSCRIBE = 8;
    const SUBACK = 9;
    const UNSUBSCRIBE = 10;
    const UNSUBACK = 11;
    const PINGREQ = 12;
    const PINGRESP = 13;
    const DISCONNECT = 14;

    protected $command;

    protected $qos;
    /**
     * @var string topic
     */
    protected $topic;
    /**
     * @var int retain
     */
    protected $retain;
    /**
     * @var int requested QoS in subscribe
     */
    protected $requestSubscribeQos;
    /**
     * @var string message body
     */
    protected $payload;
    /**
     * @var int packet identifier
     */
    protected $packetId;
    /**
     * @var int remaining length
     */
    protected $remainLen;
    /**
     * @var array connect info
     */
    protected $connectInfo = [];
    protected $dup;
    /*
     * dup flag
     */
    protected $rawData;
    /**
     * @var string ack(reply) message
     */
    public $ack;
    /**
     * @var string buffer
     */
    private $buffer;

    function __construct(string $buffer)
    {
        $this->rawData = $buffer;
        $this->buffer = $buffer;
        $this->decodeFixedHeader();
        switch ($this->command) {
            case self::CONNECT:
            {
                $this->decodeConnect();
                break;
            }
            case self::PUBLISH:
            {
                $this->decodePublish();
                break;
            }

            case self::PUBACK:
            {
                $this->decodePubAck();
                break;
            }

            case self::PUBREC:
            {
                $this->decodePubRec();
                break;
            }

            case self::PUBREL:
            {
                $this->decodePubRel();
                break;
            }

            case self::PUBCOMP:
            {
                $this->decodePubComp();
                break;
            }

            case self::SUBSCRIBE:
            {
                $this->decodeSubscribe();
                break;
            }

            case self::UNSUBSCRIBE:
            {
                $this->decodeUnSubscribe();
                break;
            }

            case self::PINGREQ:
            {
                $this->decodePingReq();
                break;
            }

            case self::DISCONNECT:
            {
                //not action
                break;
            }

            default:
            {

            }
        }
    }

    private function decodeFixedHeader()
    {
        $byte = $this->bufferPop(static::FL_FIXED);
        $byte = ord($byte);
        $this->command = ($byte & 0xF0) >> 4;
        $this->dup = ($byte & 0x08) >> 3;
        $this->qos = ($byte & 0x06) >> 1;
        $this->retain = $byte & 0x01;
        $this->remainLen = $this->getRemainBufferLen();
    }

    /**
     * @param int $flag
     * @param int $len
     * @return string
     */
    private function bufferPop($flag = 1, $len = 1): string
    {
        if ($flag === 1) {
            $len = 256 * ord($this->bufferPop(0)) + ord($this->bufferPop(0));
        }
        if (strlen($this->buffer) < $len) {
            return '';
        }
        preg_match('/^([\x{00}-\x{ff}]{' . $len . '})([\x{00}-\x{ff}]*)$/s', $this->buffer, $matches);
        $this->buffer = $matches[2];
        return $matches[1];
    }

    private function getRemainBufferLen(): int
    {
        $multiplier = 1;
        $value = 0;
        do {
            $encodedByte = ord($this->bufferPop(static::FL_FIXED));
            $value += ($encodedByte & 127) * $multiplier;
            if ($multiplier > 128 * 128 * 128) $value = -1;
            $multiplier *= 128;
        } while (($encodedByte & 128) != 0);
        return $value;
    }

    private function decodeConnect()
    {
        $info = [];
        $info['protocol'] = $this->bufferPop();
        $info['version'] = ord($this->bufferPop(static::FL_FIXED));
        $byte = ord($this->bufferPop(static::FL_FIXED));
        $info['auth'] = ($byte & 0x80) >> 7;
        $info['auth'] &= ($byte & 0x40) >> 6;
        $info['willRetain'] = ($byte & 0x20) >> 5;
        $info['willQos'] = ($byte & 0x18) >> 3;
        $info['willFlag'] = ($byte & 0x04);
        $info['cleanSession'] = ($byte & 0x02) >> 1;
        $info['reserved'] = ($byte & 0x01);
        $keep_alive = $this->bufferPop(0, 2);
        $info['keepAlive'] = 256 * ord($keep_alive[0]) + ord($keep_alive[1]);
        $info['clientId'] = $this->bufferPop();
        if ($info['auth']) {
            $info['username'] = $this->bufferPop();
            $info['password'] = $this->bufferPop();
        }
        $this->connectInfo = $info;
        $this->replyConack(0x00);
    }

    public function replyConack($flag = 0x00)
    {
        $this->ack = chr(0x20) . chr(0x02) . chr(0) . chr($flag);
    }

    private function decodePublish()
    {
        $this->topic = $this->bufferPop();
        if ($this->qos > 0) {
            $this->packetId = $this->bufferPop(static::FL_FIXED, 2);
            if ($this->qos === 1) $this->ack = chr(0x40) . chr(0x02) . $this->packetId; //puback
            if ($this->qos === 2) $this->ack = chr(0x50) . chr(0x02) . $this->packetId; //pubrec
        }
        $this->payload = $this->buffer;
    }

    private function decodePubAck()
    {
        $this->packetId = $this->bufferPop(static::FL_FIXED, 2);
    }

    private function decodePubRec()
    {
        $this->packetId = $this->bufferPop(static::FL_FIXED, 2);
        $this->ack = chr(0x62) . chr(0x02) . $this->packetId; //pubrel
    }

    private function decodePubRel()
    {
        if ($this->qos !== 1) throw new Exception(' bad buffer #' . __METHOD__);
        $this->packetId = $this->bufferPop(static::FL_FIXED, 2);
        $this->ack = chr(0x70) . chr(0x02) . $this->packetId; //pubcomp
    }

    private function decodePubComp()
    {
        $this->packetId = $this->bufferPop(static::FL_FIXED, 2);
    }

    private function decodeSubscribe()
    {
        if ($this->qos !== 1) throw new Exception(' bad buffer #' . __METHOD__);
        $this->packetId = $this->bufferPop(static::FL_FIXED, 2);
        $this->topic = $this->bufferPop();
        $this->requestSubscribeQos = ord($this->bufferPop(static::FL_FIXED));
        $payload = chr($this->requestSubscribeQos);
        $this->ack = chr(0x90) . ($payload === '' ? chr(0x02) : chr(0x02 + strlen($payload))) . $this->packetId . $payload; //suback
    }

    private function decodeUnSubscribe()
    {
        if ($this->qos !== 1) throw new Exception(' bad buffer #' . __METHOD__);
        $this->packetId = $this->bufferPop(static::FL_FIXED, 2);
        $this->topic = $this->bufferPop();
        $this->ack = chr(0xB0) . chr(0x02) . $this->packetId;  //unsuback
    }

    private function decodePingReq()
    {
        $this->ack = chr(0xD0) . chr(0);
    }

    public static function byteToBit(string $byte)
    {
        $bin = decbin(ord($byte));
        $bin = str_pad($bin, 8, 0, STR_PAD_LEFT);
        $ret = [];
        $i = 7;
        while ($i >= 0) {
            $ret[7 - $i] = (int)$bin[$i];
            $i--;
        }
        return $ret;
    }

    public static function printStr($string)
    {
        $strlen = strlen($string);
        for ($j = 0; $j < $strlen; $j++) {
            $num = ord($string{$j});
            if ($num > 31)
                $chr = $string{$j};
            else
                $chr = " ";
            printf("%4d: %08b : 0x%02x : %s \n", $j, $num, $num, $chr);
        }
    }

    public function __toString()
    {
        return '#COMMAND:' . $this->command . '  #Topic:' . $this->topic . '  #Msg:' . $this->payload . PHP_EOL;
    }

    /**
     * @return mixed
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param mixed $command
     */
    public function setCommand($command): void
    {
        $this->command = $command;
    }

    /**
     * @return mixed
     */
    public function getQos()
    {
        return $this->qos;
    }

    /**
     * @param mixed $qos
     */
    public function setQos($qos): void
    {
        $this->qos = $qos;
    }

    /**
     * @return string
     */
    public function getTopic(): string
    {
        return $this->topic;
    }

    /**
     * @param string $topic
     */
    public function setTopic(string $topic): void
    {
        $this->topic = $topic;
    }

    /**
     * @return int
     */
    public function getRetain(): int
    {
        return $this->retain;
    }

    /**
     * @param int $retain
     */
    public function setRetain(int $retain): void
    {
        $this->retain = $retain;
    }

    /**
     * @return int
     */
    public function getRequestSubscribeQos(): int
    {
        return $this->requestSubscribeQos;
    }

    /**
     * @param int $requestSubscribeQos
     */
    public function setRequestSubscribeQos(int $requestSubscribeQos): void
    {
        $this->requestSubscribeQos = $requestSubscribeQos;
    }

    /**
     * @return string
     */
    public function getPayload(): string
    {
        return $this->payload;
    }

    /**
     * @param string $payload
     */
    public function setPayload(string $payload): void
    {
        $this->payload = $payload;
    }

    /**
     * @return int
     */
    public function getPacketId()
    {
        return $this->packetId;
    }

    /**
     * @param int $packetId
     */
    public function setPacketId(int $packetId): void
    {
        $this->packetId = $packetId;
    }

    /**
     * @return array
     */
    public function getConnectInfo(): array
    {
        return $this->connectInfo;
    }

    /**
     * @param array $connectInfo
     */
    public function setConnectInfo(array $connectInfo): void
    {
        $this->connectInfo = $connectInfo;
    }

    public function getRawData()
    {
        return $this->rawData;
    }
}
