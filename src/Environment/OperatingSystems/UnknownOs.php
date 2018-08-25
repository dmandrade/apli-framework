<?php
/**
 *  Copyright (c) 2017 Danilo Andrade (http://daniloandrade.net).
 *
 *  This file is part of the Aplí Framework.
 *
 * @project Aplí Framework
 * @file Unknown.php
 *
 * @author Danilo Andrade <danilo@daniloandrade.net>
 * @date 09/12/17 at 22:30
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
