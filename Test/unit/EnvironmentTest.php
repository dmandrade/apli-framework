<?php
/**
 *  Copyright (c) 2017 Danilo Andrade (http://daniloandrade.net)
 *
 *  This file is part of the Aplí Framework.
 *
 * @project Aplí Framework
 * @file EnvironmentTest.php
 * @author Danilo Andrade <danilo@daniloandrade.net>
 * @date 05/12/17 at 11:22
 * @copyright  Copyright (c) 2017 Danilo Andrade
 * @license    GNU Lesser General Public License version 3 or later.
 */

namespace Apli\Environment\Test;

use Apli\Environment\Environment;

class EnvironmentTest extends \Codeception\Test\Unit {
    /**
     * Test instance.
     *
     * @var Environment
     */
    protected $instance;

    /**
     * getOSTestData
     *
     * @return  array
     */
    public function getIsOsxTestData() {
        return [
            [ 'CYGWIN_NT-5.1', false ],
            [ 'Darwin', true ],
            [ 'FreeBSD', false ],
            [ 'Linux', false ],
            [ 'NetBSD', false ],
            [ 'OpenBSD', false ],
            [ 'SunOS', false ],
            [ 'WIN32', false ],
            [ 'WINNT', false ],
            [ 'Windows', false ]
        ];
    }

    /**
     * getOSTestData
     *
     * @return  array
     */
    public function getIsWinTestData() {
        return [
            [ 'CYGWIN_NT-5.1', false ],
            [ 'Darwin', false ],
            [ 'FreeBSD', false ],
            [ 'Linux', false ],
            [ 'NetBSD', false ],
            [ 'OpenBSD', false ],
            [ 'SunOS', false ],
            [ 'WIN32', true ],
            [ 'WINNT', true ],
            [ 'Windows', true ]
        ];
    }

    /**
     * getOSTestData
     *
     * @return  array
     */
    public function getIsUnixTestData() {
        return [
            [ 'CYGWIN_NT-5.1', false ],
            [ 'Darwin', true ],
            [ 'FreeBSD', true ],
            [ 'Linux', true ],
            [ 'NetBSD', true ],
            [ 'OpenBSD', true ],
            [ 'SunOS', true ],
            [ 'WIN32', false ],
            [ 'WINNT', false ],
            [ 'Windows', false ]
        ];
    }

    /**
     * getOSTestData
     *
     * @return  array
     */
    public function getIsUnixOnWindowsTestData() {
        return [
            [ 'CYGWIN_NT-5.1', true ],
            [ 'Darwin', false ],
            [ 'FreeBSD', false ],
            [ 'Linux', false ],
            [ 'NetBSD', false ],
            [ 'OpenBSD', false ],
            [ 'SunOS', false ],
            [ 'WIN32', false ],
            [ 'WINNT', false ],
            [ 'Windows', false ]
        ];
    }

    /**
     * Method to test isOsx().
     *
     * @param string $os
     * @param boolean $value
     *
     * @return void
     *
     * @dataProvider getIsOsxTestData
     *
     * @covers Environment::isOsx
     */
    public function testIsOsx($os, $value) {
        $this->instance->setKernelName( $os );

        $this->assertEquals( $value, $this->instance->isOsx() );
    }

    /**
     * Method to test isWindows().
     *
     * @param string $os
     * @param boolean $value
     *
     * @return void
     *
     * @dataProvider getIsWinTestData
     *
     * @covers       Environment::isWindows
     */
    public function testIsWin( $os, $value ) {
        $this->instance->setKernelName( $os );

        $this->assertEquals( $value, $this->instance->isWindows() );
    }

    /**
     * Method to test isUnix().
     *
     * @param string $os
     * @param boolean $value
     *
     * @return void
     *
     * @dataProvider getIsUnixTestData
     *
     * @covers       Environment::isUnix
     */
    public function testIsUnix( $os, $value ) {
        $this->instance->setKernelName( $os );

        $this->assertEquals( $value, $this->instance->isUnix() );
    }

    protected function _before() {
        $this->instance = new Environment();
    }

    protected function _after() {
    }
}
