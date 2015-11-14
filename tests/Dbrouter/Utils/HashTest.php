<?php namespace Dbrouter\Utils;

use PHPUnit_Framework_TestCase;
use stdClass;

/**
 * Simple hash object.
 *
 * @package    Dbrouter
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class HashTest extends PHPUnit_Framework_TestCase
{

    public function testNewHash()
    {
        $hash = new Hash('test');

        $this->assertInstanceOf('\Dbrouter\Utils\Hash', $hash);
        $this->assertEquals('a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', $hash->getHash());
    }

    public function testHashFromObject()
    {
        $obj = new stdClass();
        $obj->name = 'test';

        $hash = new Hash($obj);

        $this->assertInstanceOf('\Dbrouter\Utils\Hash', $hash);
        $this->assertRegExp('/^[a-fA-F0-9]{40}/', $hash->getHash());
    }

    public function testHashFromArray()
    {
        $hash = new Hash(array('name' => 'test'));

        $this->assertInstanceOf('\Dbrouter\Utils\Hash', $hash);
        $this->assertEquals('c8bb3d475dd29ad6c3acb48b14b8bb53296e3c20', $hash->getHash());
    }

    public function testHashFromNumber()
    {
        $hash = new Hash(123);

        $this->assertInstanceOf('\Dbrouter\Utils\Hash', $hash);
        $this->assertEquals('40bd001563085fc35165329ea1ff5c5ecbdbbeef', $hash->getHash());
    }

    public function testValidateHash()
    {
        $this->assertFalse(Hash::valid('test'));
        $this->assertTrue(Hash::valid('a94a8fe5ccb19ba61c4c0873d391e987982fbbd3'));
    }

    public function testInstanceCompare()
    {
        $hash = new Hash('test');

        $this->assertFalse(($hash == 'test'));
        $this->assertTrue(($hash =='a94a8fe5ccb19ba61c4c0873d391e987982fbbd3'));
    }

    public function testReset()
    {
        $hash = new Hash('test');

        $this->assertFalse(($hash == 'test'));
        $this->assertTrue(($hash =='a94a8fe5ccb19ba61c4c0873d391e987982fbbd3'));

        $hash->resetHash('test2');
        $this->assertFalse(($hash =='a94a8fe5ccb19ba61c4c0873d391e987982fbbd3'));
        $this->assertEquals('109f4b3c50d7b0df729d299bc6f8e9ef9066971f', $hash->getHash());
    }
}