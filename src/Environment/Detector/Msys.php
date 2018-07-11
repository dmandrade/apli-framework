<?php
/**
 *  Copyright (c) 2017 Danilo Andrade (http://daniloandrade.net)
 *
 *  This file is part of the Aplí Framework.
 *
 * @project Aplí Framework
 * @file Msys.php
 * @author Danilo Andrade <danilo@daniloandrade.net>
 * @date 09/12/17 at 21:47
 * @copyright  Copyright (c) 2017 Danilo Andrade
 * @license    GNU Lesser General Public License version 3 or later.
 */

/**
 * Created by PhpStorm.
 * User: danil
 * Date: 09/12/2017
 * Time: 19:48
 */

namespace Apli\Environment\Detector;


class Msys implements OsInterface
{

    /**
     * @return array
     */
    public function getVariants()
    {
        return [
            'MINGW',
            'MINGW32_NT-6.1',
            'MSYS_NT-6.1'
        ];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Msys';
    }

    /**
     * @return int
     */
    public function getFamily()
    {
        return OsDetector::UNIX_ON_WINDOWS_FAMILY;
    }
}
