<?php
/**
 *  Copyright (c) 2018 Danilo Andrade (http://daniloandrade.net)
 *
 *  This file is part of the Aplí Framework.
 *
 * @project Aplí Framework
 * @file Environment.php
 * @author Danilo Andrade <danilo@daniloandrade.net>
 * @date 07/07/18 at 17:10
 * @copyright  Copyright (c) 2018 Danilo Andrade
 * @license    GNU Lesser General Public License version 3 or later.
 */

namespace Apli\Session;

use Apli\Support\Enum;

/**
 * Class State
 *
 * @method static State ACTIVE()
 * @method static State INACTIVE()
 * @method static State EXPIRED()
 * @method static State DESTROYED()
 * @method static State ERROR()
 *
 * @package Apli\Session
 */
final class State extends Enum
{
    const ACTIVE = 'active';

    const INACTIVE = 'inactive';

    const EXPIRED = 'expired';

    const DESTROYED = 'destroyed';

    const ERROR = 'error';
}