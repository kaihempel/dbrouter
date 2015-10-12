<?php namespace Dbrouter\Exception\Target;

use PHPUnit_Framework_TestCase;

/**
 * Basic target exception test
 *
 * @package    Dbrouter
 * @subpackage tests
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class TargetExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testNewException() {

        $ex = new TargetException('Special exception');

        $this->assertInstanceOf('Dbrouter\Exception\Target\TargetException', $ex);
        $this->assertEquals('Special exception', $ex->getMessage());
        $this->assertInternalType('array', $ex->getErrorInformations());
        $this->assertInternalType('array', $ex->getTargetInformations());
    }

    public function testMake() {

        $ex = TargetException::make('Make test');

        $this->assertInstanceOf('Dbrouter\Exception\Target\TargetException', $ex);
        $this->assertEquals('Make test', $ex->getMessage());
    }

    public function testMakeWithTargets() {

        $ex1 = TargetException::make('Make test closure', 'ClosureTarget');

        $this->assertInstanceOf('Dbrouter\Exception\Target\TargetException', $ex1);
        $this->assertEquals('Make test closure', $ex1->getMessage());
        $this->assertEquals('unknown', $ex1->getType());

        $ex1->setType(TargetException::TARGETTYPE_CLASS);

        $this->assertEquals('class', $ex1->getType());

    }
}