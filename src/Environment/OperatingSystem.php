<?php
/**
 *  Copyright (c) 2018 Danilo Andrade
 *
 *  This file is part of the apli project.
 *
 *  @project apli
 *  @file OperatingSystem.php
 *  @author Danilo Andrade <danilo@webbingbrasil.com.br>
 *  @date 27/08/18 at 10:27
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
     * @return array
     */
    public static function getVariants();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getFamily();

    public function __toString();
}
