<?php
/**
 *  Copyright (c) 2018 Danilo Andrade
 *
 *  This file is part of the apli project.
 *
 * @project apli
 * @file Cygwin.php
 * @author Danilo Andrade <danilo@webbingbrasil.com.br>
 * @date 27/08/18 at 10:27
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

class Cygwin implements OperatingSystem
{
    /**
     * @return array
     */
    public static function getVariants()
    {
        return [
            'CYGWIN',
            'CYGWIN_NT-5.1',
            'CYGWIN_NT-6.1',
            'CYGWIN_NT-6.1-WOW64',
        ];
    }

    /**
     * @return int
     */
    public function getFamily()
    {
        return SystemDetector::UNIX_ON_WINDOWS_FAMILY;
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

    /**
     * @return string
     */
    public function getName()
    {
        return 'Cygwin';
    }
}
