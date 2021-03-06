<?php
/**
 *  Copyright (c) 2018 Danilo Andrade.
 *
 *  This file is part of the apli project.
 *
 * @project apli
 * @file GroupGenerator.php
 *
 * @author Danilo Andrade <danilo@webbingbrasil.com.br>
 * @date 27/08/18 at 10:26
 */

/**
 * Created by PhpStorm.
 * User: Danilo
 * Date: 25/08/2018
 * Time: 14:06.
 */

namespace Apli\Router\DataGenerator;

class GroupGenerator extends AbstractGenerator
{
    /**
     * @return int
     */
    protected function getApproxChunkSize(): int
    {
        return 10;
    }

    /**
     * @param array $regexToRoutesMap
     * @return array
     */
    protected function processChunk(array $regexToRoutesMap): array
    {
        $routeMap = [];
        $regexes = [];
        $numGroups = 0;
        foreach ($regexToRoutesMap as $regex => $route) {
            $numVariables = count($route->getVariables());
            $numGroups = max($numGroups, $numVariables);
            $regexes[] = $regex.str_repeat('()', $numGroups - $numVariables);
            $routeMap[$numGroups + 1] = [$route, $route->getVariables()];
            $numGroups++;
        }
        $regex = '~^(?|'.implode('|', $regexes).')$~';

        return ['regex' => $regex, 'routeMap' => $routeMap];
    }
}
