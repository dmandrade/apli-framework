<?php
/**
 *  Copyright (c) 2018 Danilo Andrade.
 *
 *  This file is part of the apli project.
 *
 * @project apli
 * @file State.php
 *
 * @author Danilo Andrade <danilo@webbingbrasil.com.br>
 * @date 27/08/18 at 10:26
 */

namespace Apli\Session;

use Apli\Support\AbstractEnum;

/**
 * Class State.
 *
 * @method static State ACTIVE()
 * @method static State INACTIVE()
 * @method static State EXPIRED()
 * @method static State DESTROYED()
 * @method static State ERROR()
 */
final class State extends AbstractEnum
{
    const ACTIVE = 'active';
    const INACTIVE = 'inactive';
    const EXPIRED = 'expired';
    const DESTROYED = 'destroyed';
    const ERROR = 'error';
}
