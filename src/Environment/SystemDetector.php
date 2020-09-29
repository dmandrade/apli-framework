<?php
/**
 *  Copyright (c) 2018 Danilo Andrade.
 *
 *  This file is part of the apli project.
 *
 * @project apli
 * @file SystemDetector.php
 *
 * @author Danilo Andrade <danilo@webbingbrasil.com.br>
 * @date 27/08/18 at 10:27
 */

/**
 * Created by PhpStorm.
 * User: danil
 * Date: 09/12/2017
 * Time: 20:23.
 */

namespace Apli\Environment;

use Apli\Environment\OperatingSystems\Bsd;
use Apli\Environment\OperatingSystems\Cygwin;
use Apli\Environment\OperatingSystems\Linux;
use Apli\Environment\OperatingSystems\MacOsx;
use Apli\Environment\OperatingSystems\Msys;
use Apli\Environment\OperatingSystems\Sun;
use Apli\Environment\OperatingSystems\UnknownOs;
use Apli\Environment\OperatingSystems\Windows;
use InvalidArgumentException;
use ReflectionClass;

class SystemDetector
{
    const OTHER_FAMILY = 0;
    const UNIX_FAMILY = 1;
    const WINDOWS_FAMILY = 2;
    const UNIX_ON_WINDOWS_FAMILY = 3;

    /**
     * @var OperatingSystem[]
     */
    private static $operatingSystems = [
        Bsd::class,
        Cygwin::class,
        Linux::class,
        MacOsx::class,
        Msys::class,
        Sun::class,
        Windows::class,
    ];

    /**
     * Register a new operating system.
     *
     * @param $class
     *
     * @throws \ReflectionException
     * @throws InvalidArgumentException
     */
    public static function registerOperatingSystem($class)
    {
        $reflection = new ReflectionClass($class);
        if (!$reflection->implementsInterface(OperatingSystem::class)) {
            throw new InvalidArgumentException($reflection->getName().' should implements OperatingSystem');
        }

        if (!in_array($class, self::$operatingSystems)) {
            self::$operatingSystems[] = $class;
        }
    }

    /**
     * @param $kernel
     *
     * @return OperatingSystem
     */
    public function detect($kernel)
    {
        foreach (self::$operatingSystems as $class) {
            $detected = $this->isOs($kernel, $class::getVariants());
            if ($detected) {
                return new $class();
            }
        }

        return new UnknownOs();
    }

    /**
     * Check if kernel is from operating system variants.
     *
     * @param $kernel
     * @param $variants
     *
     * @return bool
     */
    private function isOs($kernel, $variants)
    {
        return (bool) preg_grep('/^'.preg_quote($kernel).'$/i', $variants);
    }
}
