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
     * Property os.
     *
     * @var boolean
     */
    protected $os;

    /**
     * Property isWin.
     *
     * @var  boolean
     */
    protected $isWin;

    /**
     * Property isMac.
     *
     * @var  boolean
     */
    protected $isMac;

    /**
     * Property isUnix.
     *
     * @var  boolean
     */
    protected $isUnix;

    /**
     * Property isLinux.
     *
     * @var  boolean
     */
    protected $isLinux;

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
            [ 'HP-UX', false ],
            [ 'IRIX64', false ],
            [ 'Linux', false ],
            [ 'NetBSD', false ],
            [ 'OpenBSD', false ],
            [ 'SunOS', false ],
            [ 'Unix', false ],
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
            [ 'CYGWIN_NT-5.1', true ],
            [ 'Darwin', true ],
            [ 'FreeBSD', true ],
            [ 'HP-UX', true ],
            [ 'IRIX64', true ],
            [ 'Linux', true ],
            [ 'NetBSD', true ],
            [ 'OpenBSD', true ],
            [ 'SunOS', true ],
            [ 'Unix', true ],
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
    public function getIsLinuxTestData() {
        return [
            [ 'CYGWIN_NT-5.1', false ],
            [ 'Darwin', false ],
            [ 'FreeBSD', false ],
            [ 'HP-UX', false ],
            [ 'IRIX64', false ],
            [ 'Linux', true ],
            [ 'NetBSD', false ],
            [ 'OpenBSD', false ],
            [ 'SunOS', false ],
            [ 'Unix', false ],
            [ 'WIN32', false ],
            [ 'WINNT', false ],
            [ 'Windows', false ]
        ];
    }

    /**
     * Method to test getOS().
     *
     * @return void
     *
     * @covers Environment::getOS
     */
    public function testGetOS() {
        $this->instance->setUname( 'Darwin' );

        $this->assertEquals( 'DAR', $this->instance->getOS() );
    }

    /**
     * Method to test isWin().
     *
     * @param string $os
     * @param boolean $value
     *
     * @return void
     *
     * @dataProvider getIsWinTestData
     *
     * @covers       Environment::isWin
     */
    public function testIsWin( $os, $value ) {
        $this->instance->setOS( null );
        $this->instance->setUname( $os );

        $this->assertEquals( $value, $this->instance->isWin() );
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
        $this->instance->setOS( null );
        $this->instance->setUname( $os );

        $this->assertEquals( $value, $this->instance->isUnix() );
    }

    /**
     * Method to test isLinux().
     *
     * @param string $os
     * @param boolean $value
     *
     * @return void
     *
     * @dataProvider getIsLinuxTestData
     *
     * @covers       Environment::isLinux
     */
    public function testIsLinux( $os, $value ) {
        $this->instance->setOS( null );
        $this->instance->setUname( $os );

        $this->assertEquals( $value, $this->instance->isLinux() );
    }

    protected function _before() {
        $this->instance = new Environment();

        // Detect the native operating system type.
        $this->os = strtoupper( substr( PHP_OS, 0, 3 ) );

        $this->isWin = $this->os === 'WIN';

        $this->isMac = $this->os === 'MAC';

        $this->isUnix = in_array( $this->os, [ 'CYG', 'DAR', 'FRE', 'LIN', 'NET', 'OPE', 'MAC' ] );

        $this->isLinux = $this->os === 'LIN';
    }

    protected function _after() {
    }
}
