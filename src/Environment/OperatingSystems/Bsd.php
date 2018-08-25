<?php
/**
 *  Copyright (c) 2017 Danilo Andrade (http://daniloandrade.net).
 *
 *  This file is part of the Aplí Framework.
 *
 * @project Aplí Framework
 * @file Bsd.php
 *
 * @author Danilo Andrade <danilo@daniloandrade.net>
 * @date 09/12/17 at 21:58
 *
 * @copyright  Copyright (c) 2017 Danilo Andrade
 * @license    GNU Lesser General Public License version 3 or later.
 */

/**
 * Created by PhpStorm.
 * User: danil
 * Date: 09/12/2017
 * Time: 19:48.
 */

namespace Apli\Environment\OperatingSystems;

use Apli\Environment\OperatingSystem;
use Apli\Environment\SystemDetector;

class Bsd implements OperatingSystem
{
    /**
     * @return array
     */
    public static function getVariants()
    {
        return [
            'DRAGONFLY',
            'OPENBSD',
            'FREEBSD',
            'NETBSD',
            'GNU/KFREEBSD',
            'GNU/FREEBSD',
            'DEBIAN/FREEBSD',
        ];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'BSD';
    }

    /**
     * @return int
     */
    public function getFamily()
    {
        return SystemDetector::UNIX_FAMILY;
    }

    /**
     * Operating system name
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
