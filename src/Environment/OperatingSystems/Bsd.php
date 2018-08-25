<?php
/**
 *  Copyright (c) 2018 Danilo Andrade
 *
 *  This file is part of the apli project.
 *
 *  @project apli
 *  @file Bsd.php
 *  @author Danilo Andrade <danilo@webbingbrasil.com.br>
 *  @date 25/08/18 at 07:47
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
