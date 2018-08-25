<?php
/**
 *  Copyright (c) 2018 Danilo Andrade
 *
 *  This file is part of the apli project.
 *
 *  @project apli
 *  @file Windows.php
 *  @author Danilo Andrade <danilo@webbingbrasil.com.br>
 *  @date 25/08/18 at 07:48
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

class Windows implements OperatingSystem
{
    /**
     * @return array
     */
    public static function getVariants()
    {
        return [
            'WINDOWS',
            'WINNT',
            'WIN32',
            'INTERIX',
            'UWIN',
            'UWIN-W7',
        ];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Windows';
    }

    /**
     * @return int
     */
    public function getFamily()
    {
        return SystemDetector::WINDOWS_FAMILY;
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
