<?php
/*
 *  Copyright (c) 2020 Danilo Andrade
 *
 *  This file is part of the apli project.
 *
 *  @project apli
 *  @file CacheInterface.php
 *  @author Danilo Andrade <danilo@webbingbrasil.com.br>
 *  @date 13/09/20 at 17:24
 */

namespace Apli\MQTT;

interface CacheInterface
{
    function set(string $key,$data);
    function get(string $key);
    function delete(string $key);
}
