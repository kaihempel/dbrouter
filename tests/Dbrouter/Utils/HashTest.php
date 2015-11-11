<?php namespace Dbrouter\Utils;

use PHPUnit_Framework_TestCase;

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

    public function testValidateHash()
    {
        $this->assertFalse(Hash::valid('test'));
        $this->assertTrue(Hash::valid('a94a8fe5ccb19ba61c4c0873d391e987982fbbd3'));
    }

}