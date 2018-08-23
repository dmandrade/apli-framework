<?php
/**
 *  Copyright (c) 2017 Danilo Andrade (http://daniloandrade.net).
 *
 *  This file is part of the Aplí Framework.
 *
 * @project Aplí Framework
 * @file Linux.php
 *
 * @author Danilo Andrade <danilo@daniloandrade.net>
 * @date 09/12/17 at 21:49
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

class Linux implements OperatingSystem
{
    /**
     * @return array
     */
    public function getVariants()
    {
        return [
            'LINUX',
            'GNU',
            'GNU/LINUX',
        ];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Linux';
    }

    /**
     * @return int
     */
    public function getFamily()
    {
        return SystemDetector::UNIX_FAMILY;
    }
}
