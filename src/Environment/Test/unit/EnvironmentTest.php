<?php
/**
 *  Copyright (c) 2018 Danilo Andrade.
 *
 *  This file is part of the apli project.
 *
 * @project apli
 * @file EnvironmentTest.php
 *
 * @author Danilo Andrade <danilo@webbingbrasil.com.br>
 * @date 27/08/18 at 10:27
 */

namespace Apli\Environment\Test;

use Apli\Environment\Environment;

class EnvironmentTest extends \Codeception\Test\Unit
{
    /**
     * Test instance.
     *
     * @var Environment
     */
    protected $environment;

    /**
     * Is hhvm flag.
     *
     * @var bool
     */
    protected $isHHVM;

    /**
     * PHP/HHVM runtime version.
     *
     * @var string
     */
    protected $phpVersion;

    /**
     * getOSTestData.
     *
     * @return array
     */
    public function getIsOsxTestData()
    {
        return [
            ['CYGWIN_NT-5.1', false],
            ['Darwin', true],
            ['FreeBSD', false],
            ['Linux', false],
            ['NetBSD', false],
            ['OpenBSD', false],
            ['SunOS', false],
            ['WIN32', false],
            ['WINNT', false],
            ['Windows', false],
        ];
    }

    /**
     * getOSTestData.
     *
     * @return array
     */
    public function getIsWinTestData()
    {
        return [
            ['CYGWIN_NT-5.1', false],
            ['Darwin', false],
            ['FreeBSD', false],
            ['Linux', false],
            ['NetBSD', false],
            ['OpenBSD', false],
            ['SunOS', false],
            ['WIN32', true],
            ['WINNT', true],
            ['Windows', true],
        ];
    }

    /**
     * getOSTestData.
     *
     * @return array
     */
    public function getIsUnixTestData()
    {
        return [
            ['CYGWIN_NT-5.1', false],
            ['Darwin', true],
            ['FreeBSD', true],
            ['Linux', true],
            ['NetBSD', true],
            ['OpenBSD', true],
            ['SunOS', true],
            ['WIN32', false],
            ['WINNT', false],
            ['Windows', false],
        ];
    }

    /**
     * getOSTestData.
     *
     * @return array
     */
    public function getIsUnixOnWindowsTestData()
    {
        return [
            ['CYGWIN_NT-5.1', true],
            ['Darwin', false],
            ['FreeBSD', false],
            ['Linux', false],
            ['NetBSD', false],
            ['OpenBSD', false],
            ['SunOS', false],
            ['WIN32', false],
            ['WINNT', false],
            ['Windows', false],
        ];
    }

    /**
     * getIsCliTestData.
     *
     * @see http://php.net/manual/en/function.php-sapi-name.php
     *
     * @return array
     */
    public function getIsCliTestData()
    {
        return [
            ['aolserver', false],
            ['apache', false],
            ['apache2filter', false],
            ['apache2handler', false],
            ['caudium', false],
            ['cgi', false],
            ['cgi-fcgi', false],
            ['cli', true],
            ['cli-server', true],
            ['cgi', false],
            ['continuity', false],
            ['embed', false],
            ['fpm-fcgi', false],
            ['isapi', false],
            ['litespeed', false],
            ['milter', false],
            ['nsapi', false],
            ['phttpd', false],
            ['pi3web', false],
            ['roxen', false],
            ['thttpd', false],
            ['tux', false],
            ['webjames', false],
        ];
    }

    /**
     * getIsWebTestData.
     *
     * @see http://php.net/manual/en/function.php-sapi-name.php
     *
     * @return array
     */
    public function getIsWebTestData()
    {
        return [
            ['aolserver', true],
            ['apache', true],
            ['apache2filter', true],
            ['apache2handler', true],
            ['caudium', true],
            ['cgi', true],
            ['cgi-fcgi', true],
            ['cli', false],
            ['cli-server', false],
            ['cgi', true],
            ['continuity', true],
            ['embed', true],
            ['fpm-fcgi', true],
            ['isapi', true],
            ['litespeed', true],
            ['milter', true],
            ['nsapi', true],
            ['phttpd', true],
            ['pi3web', true],
            ['roxen', true],
            ['thttpd', true],
            ['tux', true],
            ['webjames', true],
        ];
    }

    /**
     * Method to test isOsx().
     *
     * @param string $os
     * @param bool   $value
     *
     * @return void
     *
     * @dataProvider getIsOsxTestData
     *
     * @covers       Environment::isOsx
     */
    public function testIsOsx($os, $value)
    {
        $this->environment->server()->platform()->setKernelName($os);

        $this->assertEquals($value, $this->environment->server()->platform()->isOsx());
    }

    /**
     * Method to test isWindows().
     *
     * @param string $os
     * @param bool   $value
     *
     * @return void
     *
     * @dataProvider getIsWinTestData
     *
     * @covers       Environment::isWindows
     */
    public function testIsWin($os, $value)
    {
        $this->environment->server()->platform()->setKernelName($os);

        $this->assertEquals($value, $this->environment->server()->platform()->isWindows());
    }

    /**
     * Method to test isUnix().
     *
     * @param string $os
     * @param bool   $value
     *
     * @return void
     *
     * @dataProvider getIsUnixTestData
     *
     * @covers       Environment::isUnix
     */
    public function testIsUnix($os, $value)
    {
        $this->environment->server()->platform()->setKernelName($os);

        $this->assertEquals($value, $this->environment->server()->platform()->isUnix());
    }

    /**
     * Method to test isUnixOnWindows().
     *
     * @param string $os
     * @param bool   $value
     *
     * @return void
     *
     * @dataProvider getIsUnixOnWindowsTestData
     *
     * @covers       Environment::isUnixOnWindows
     */
    public function testIsUnixOnWindows($os, $value)
    {
        $this->environment->server()->platform()->setKernelName($os);

        $this->assertEquals($value, $this->environment->server()->platform()->isUnixOnWindows());
    }

    /**
     * Method to test isCli().
     *
     * @param string $interface
     * @param bool   $value
     *
     * @return void
     *
     * @dataProvider getIsCliTestData
     *
     * @covers       Environment::isCli
     */
    public function testIsCli($interface, $value)
    {
        $this->environment->setSapiName($interface);

        $this->assertEquals($value, $this->environment->isCli());
    }

    /**
     * Method to test isWeb().
     *
     * @param string $interface
     * @param bool   $value
     *
     * @return void
     *
     * @dataProvider getIsWebTestData
     *
     * @covers       Environment::isWeb
     */
    public function testIsWeb($interface, $value)
    {
        $this->environment->setSapiName($interface);

        $this->assertEquals($value, $this->environment->isWeb());
    }

    /**
     * Method to test isHHVM().
     *
     * @return void
     *
     * @covers       Environment::isHHVM
     */
    public function testIsHHVM()
    {
        $this->assertEquals($this->isHHVM, $this->environment->isHHVM());
    }

    /**
     * Method to test isPHP().
     *
     * @return void
     *
     * @covers       Environment::isPHP
     */
    public function testIsPHP()
    {
        $this->assertEquals(!$this->isHHVM, $this->environment->isPHP());
    }

    /**
     * Method to test getPhpVersion().
     *
     * @return void
     *
     * @covers       Environment::getPhpVersion
     */
    public function testGetPhpVersion()
    {
        $this->assertEquals($this->phpVersion, $this->environment->getPhpVersion());
    }

    protected function _before()
    {
        $this->environment = new Environment();
        $this->isHHVM = defined('HHVM_VERSION');
        $this->phpVersion = $this->isHHVM ? constant('HHVM_VERSION') : constant('PHP_VERSION');
    }

    protected function _after()
    {
    }
}
