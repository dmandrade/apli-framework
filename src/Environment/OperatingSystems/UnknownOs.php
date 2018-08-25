<?php
/**
 *  Copyright (c) 2018 Danilo Andrade
 *
 *  This file is part of the apli project.
 *
 *  @project apli
 *  @file UnknownOs.php
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

class UnknownOs implements OperatingSystem
{
    /**
     * Array ir kernel variant names
     *
     * @return array
     */
    public static function getVariants()
    {
        return [];
    }

    /**
     * Operating system name
     *
     * @return string
     */
    public function getName()
    {
        return 'UNKNOWN';
    }


    /**
     * Operating system family
     *
     * @return int
     */
    public function getFamily()
    {
        return SystemDetector::OTHER_FAMILY;
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
