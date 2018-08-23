<?php
/**
 *  Copyright (c) 2017 Danilo Andrade (http://daniloandrade.net).
 *
 *  This file is part of the Aplí Framework.
 *
 * @project Aplí Framework
 * @file OsDetector.php
 *
 * @author Danilo Andrade <danilo@daniloandrade.net>
 * @date 09/12/17 at 20:34
 *
 * @copyright  Copyright (c) 2017 Danilo Andrade
 * @license    GNU Lesser General Public License version 3 or later.
 */

/**
 * Created by PhpStorm.
 * User: danil
 * Date: 09/12/2017
 * Time: 20:23.
 */

namespace Apli\Environment\Detector;

class SystemDetector
{
    const OTHER_FAMILY = 0;
    const UNIX_FAMILY = 1;
    const WINDOWS_FAMILY = 2;
    const UNIX_ON_WINDOWS_FAMILY = 3;

    /**
     * @var OperatingSystem[]
     */
    private static $operationalSystems = [
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
     * @return OperatingSystem
     */
    public function detect($kernel)
    {
        foreach (self::$operationalSystems as $class) {
            $detected = $this->isOs($kernel,$class::getVariants());
            if ($detected) {
                return new $class();
            }
        }

        return new UnknownOs();
    }

    /**
     * @param string $kernel
     * @param array  $os
     *
     * @return bool
     */
    private function isOs($kernel, $variants)
    {
        return (bool) preg_grep('/^'.preg_quote($kernel).'$/i', $variants);
    }

    /**
     * Register a new operating system.
     *
     * @param OperatingSystem $class
     */
    public static function registeOperatingSystem(OperatingSystem $class)
    {
        static::macro(Str::camel(class_basename($class)), function (...$parameters) use ($class) {
            return new $class(...$parameters);
        });
    }


    /**
     * @param OperatingSystem $class
     */
    public function extend($class)
    {
        if (!in_array($class, self::$operationalSystems)) {
            self::$operationalSystems[class_basename($class)] = $class;
        }
    }
}
