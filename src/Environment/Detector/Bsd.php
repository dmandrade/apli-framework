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

namespace Apli\Environment\Detector;

class Bsd implements OsInterface
{
    /**
     * @return array
     */
    public function getVariants()
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
        return OsDetector::UNIX_FAMILY;
    }
}
