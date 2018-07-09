<?php
/**
 *  Copyright (c) 2017 Danilo Andrade (http://daniloandrade.net)
 *
 *  This file is part of the Aplí Framework.
 *
 * @project Aplí Framework
 * @file Sun.php
 * @author Danilo Andrade <danilo@daniloandrade.net>
 * @date 09/12/17 at 21:51
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


class Sun implements OsInterface {

    /**
     * @return array
     */
    public function getVariants() {
        return [
            'SOLARIS',
            'SUNOS'
        ];
    }

    /**
     * @return string
     */
    public function getName() {
        return 'Sun OS';
    }

    /**
     * @return int
     */
    public function getFamily() {
        return OsDetector::UNIX_FAMILY;
    }
}
