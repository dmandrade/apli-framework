<?php
/**
 *  Copyright (c) 2018 Danilo Andrade.
 *
 *  This file is part of the apli project.
 *
 * @project apli
 * @file Linux.php
 *
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

class Linux implements OperatingSystem
{
    /**
     * Array ir kernel variant names.
     *
     * @return array
     */
    public static function getVariants()
    {
        return [
            'LINUX',
            'GNU',
            'GNU/LINUX',
        ];
    }

    /**
     * Operating system family.
     *
     * @return int
     */
    public function getFamily()
    {
        return SystemDetector::UNIX_FAMILY;
    }

    /**
     * Operating system name.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Operating system name.
     *
     * @return string
     */
    public function getName()
    {
        return 'Linux';
    }
}
