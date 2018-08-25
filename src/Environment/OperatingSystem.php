<?php
/**
 *  Copyright (c) 2018 Danilo Andrade
 *
 *  This file is part of the apli project.
 *
 *  @project apli
 *  @file OperatingSystem.php
 *  @author Danilo Andrade <danilo@webbingbrasil.com.br>
 *  @date 25/08/18 at 07:45
 */

/**
 * Created by PhpStorm.
 * User: danil
 * Date: 09/12/2017
 * Time: 19:48.
 */

namespace Apli\Environment;

interface OperatingSystem
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getFamily();

    /**
     * @return array
     */
    public static function getVariants();

    public function __toString();
}
