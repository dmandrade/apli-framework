<?php
/**
 *  Copyright (c) 2017 Danilo Andrade (http://daniloandrade.net)
 *
 *  This file is part of the Aplí Framework.
 *
 * @project Aplí Framework
 * @file OsDetector.php
 * @author Danilo Andrade <danilo@daniloandrade.net>
 * @date 09/12/17 at 20:34
 * @copyright  Copyright (c) 2017 Danilo Andrade
 * @license    GNU Lesser General Public License version 3 or later.
 */

/**
 * Created by PhpStorm.
 * User: danil
 * Date: 09/12/2017
 * Time: 20:23
 */

namespace Apli\Environment\Detector;

class OsDetector
{

    const OTHER_FAMILY = 0;
    const UNIX_FAMILY = 1;
    const WINDOWS_FAMILY = 2;
    const UNIX_ON_WINDOWS_FAMILY = 3;

    /**
     * @var OsInterface[]
     */
    private $detectors = [
        Bsd::class,
        Cygwin::class,
        Linux::class,
        MacOsx::class,
        Msys::class,
        Sun::class,
        Windows::class,
    ];

    /**
     * @param $kernel
     *
     * @return OsInterface
     */
    public function detectOs($kernel)
    {
        foreach ($this->detectors as $class) {
            /** @var OsInterface $os */
            $os = (new $class);
            $detected = $this->isOs($kernel, $os->getVariants());
            if ($detected) {
                return $os;
            }
        }

        return (new UnknownOs());
    }

    /**
     * @param string $kernel
     * @param array $os
     *
     * @return boolean
     */
    private function isOs($kernel, $variants)
    {
        return (bool)preg_grep('/^' . preg_quote($kernel) . '$/i', $variants);
    }

    /**
     * @param OsInterface $detector
     */
    public function extend($detector)
    {
        if (!in_array($detector, $this->detectors)) {
            $this->detectors[] = $detector;
        }
    }
}
