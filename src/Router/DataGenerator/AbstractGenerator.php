<?php
/**
 *  Copyright (c) 2018 Danilo Andrade.
 *
 *  This file is part of the apli project.
 *
 * @project apli
 * @file AbstractGenerator.php
 *
 * @author Danilo Andrade <danilo@webbingbrasil.com.br>
 * @date 27/08/18 at 10:26
 */

/**
 * Created by PhpStorm.
 * User: Danilo
 * Date: 25/08/2018
 * Time: 14:04.
 */

namespace Apli\Router\DataGenerator;

use Apli\Router\DataGeneratorInterface;
use Apli\Router\Exception\BadRouteException;
use Apli\Router\Route;
use function count;
use function is_string;

abstract class AbstractGenerator implements DataGeneratorInterface
{
    /** @var mixed[][] */
    protected $staticRoutes = [];

    /** @var Route[][] */
    protected $methodToRegexToRoutesMap = [];

    /**
     * @return void
     */
    public function reset(): void
    {
        $this->staticRoutes = [];
        $this->methodToRegexToRoutesMap = [];
    }

    /**
     * @param Route $route
     * @param array $routeData
     */
    public function addRoute(Route $route, array $routeData): void
    {
        if ($this->isStaticRoute($routeData)) {
            $this->addStaticRoute($route, $routeData);

            return;
        }

        $this->addVariableRoute($route, $routeData);
    }

    /**
     * @param mixed[]
     *
     * @return bool
     */
    private function isStaticRoute($routeData): bool
    {
        return count($routeData) === 1 && is_string($routeData[0]);
    }

    /**
     * @param Route $route
     * @param array $routeData
     */
    private function addStaticRoute(Route $route, array $routeData): void
    {
        $routeStr = $routeData[0];
        if (isset($this->staticRoutes[$route->getMethod()][$routeStr])) {
            throw new BadRouteException(sprintf(
                'Cannot register two routes matching "%s" for method "%s"',
                $routeStr, $route->getMethod()
            ));
        }
        if (isset($this->methodToRegexToRoutesMap[$route->getMethod()])) {
            foreach ($this->methodToRegexToRoutesMap[$route->getMethod()] as $prevRoute) {
                if ($prevRoute->matches($routeStr)) {
                    throw new BadRouteException(sprintf(
                        'Static route "%s" is shadowed by previously defined variable route "%s" for method "%s"',
                        $routeStr, $prevRoute->getRegex(), $prevRoute->getMethod()
                    ));
                }
            }
        }
        $this->staticRoutes[$route->getMethod()][$routeStr] = $route;
    }

    /**
     * @param Route $route
     * @param array $routeData
     */
    private function addVariableRoute(Route $route, array $routeData): void
    {
        [$regex, $variables] = $this->buildRegexForRoute($routeData);
        if (isset($this->methodToRegexToRoutesMap[$route->getMethod()][$regex])) {
            throw new BadRouteException(sprintf(
                'Cannot register two routes matching "%s" for method "%s"',
                $regex, $route->getMethod()
            ));
        }

        $route->setVariables($variables);
        $route->setRegex($regex);
        $this->methodToRegexToRoutesMap[$route->getMethod()][$regex] = $route;
    }

    /**
     * @param mixed[]
     *
     * @return mixed[]
     */
    private function buildRegexForRoute($routeData): array
    {
        $regex = '';
        $variables = [];
        foreach ($routeData as $part) {
            if (is_string($part)) {
                $regex .= preg_quote($part, '~');
                continue;
            }
            [$varName, $regexPart] = $part;
            if (isset($variables[$varName])) {
                throw new BadRouteException(sprintf(
                    'Cannot use the same placeholder "%s" twice', $varName
                ));
            }
            if ($this->regexHasCapturingGroups($regexPart)) {
                throw new BadRouteException(sprintf(
                    'Regex "%s" for parameter "%s" contains a capturing group',
                    $regexPart, $varName
                ));
            }
            $variables[$varName] = $varName;
            $regex .= '('.$regexPart.')';
        }

        return [$regex, $variables];
    }

    /**
     * @param string
     *
     * @return bool
     */
    private function regexHasCapturingGroups($regex): bool
    {
        if (false === strpos($regex, '(')) {
            // Needs to have at least a ( to contain a capturing group
            return false;
        }
        // Semi-accurate detection for capturing groups
        return (bool) preg_match(
            '~
                (?:
                    \(\?\(
                  | \[ [^\]\\\\]* (?: \\\\ . [^\]\\\\]* )* \]
                  | \\\\ .
                ) (*SKIP)(*FAIL) |
                \(
                (?!
                    \? (?! <(?![!=]) | P< | \' )
                  | \*
                )
            ~x',
            $regex
        );
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        if (empty($this->methodToRegexToRoutesMap)) {
            return [$this->staticRoutes, []];
        }

        return [$this->staticRoutes, $this->generateVariableRouteData()];
    }

    /**
     * @return array
     */
    private function generateVariableRouteData(): array
    {
        $data = [];
        foreach ($this->methodToRegexToRoutesMap as $method => $regexToRoutesMap) {
            $chunkSize = $this->computeChunkSize(count($regexToRoutesMap));
            $chunks = array_chunk($regexToRoutesMap, $chunkSize, true);
            $data[$method] = array_map([$this, 'processChunk'], $chunks);
        }

        return $data;
    }

    /**
     * @param int
     *
     * @return int
     */
    private function computeChunkSize($count): int
    {
        $numParts = max(1, round($count / $this->getApproxChunkSize()));

        return (int) ceil($count / $numParts);
    }

    /**
     * @return int
     */
    abstract protected function getApproxChunkSize(): int;

    /**
     * @param $regexToRoutesMap
     * @return array
     */
    abstract protected function processChunk(array $regexToRoutesMap): array;
}
