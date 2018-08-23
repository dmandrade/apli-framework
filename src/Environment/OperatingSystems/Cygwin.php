<?php
/**
 *  Copyright (c) 2017 Danilo Andrade (http://daniloandrade.net).
 *
 *  This file is part of the Aplí Framework.
 *
 * @project Aplí Framework
 * @file Cygwin.php
 *
 * @author Danilo Andrade <danilo@daniloandrade.net>
 * @date 09/12/17 at 21:45
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

class Cygwin implements OperatingSystem
{
    /**
     * @return array
     */
    public function getVariants()
    {
        return [
            'CYGWIN',
            'CYGWIN_NT-5.1',
            'CYGWIN_NT-6.1',
            'CYGWIN_NT-6.1-WOW64',
        ];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Cygwin';
    }

    /**
     * @return int
     */
    public function getFamily()
    {
        return SystemDetector::UNIX_ON_WINDOWS_FAMILY;
    }
}
