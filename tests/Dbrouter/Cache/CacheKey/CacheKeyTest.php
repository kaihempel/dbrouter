<?php namespace Dbrouter\Cache\CacheKey;

use PHPUnit_Framework_TestCase;
use Mockery as m;

/**
 * Cache key test
 *
 * @package    Dbrouter
 * @subpackage tests
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class CacheKeyTest extends PHPUnit_Framework_TestCase
{
    public function testNewCacheKey()
    {
        $cachekey = new CacheKey('test');

        $this->assertInstanceOf('\Dbrouter\Cache\CacheKey\CacheKey', $cachekey);
        $this->assertEquals('a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', $cachekey->getHash());
        $this->assertTrue(('a94a8fe5ccb19ba61c4c0873d391e987982fbbd3' == $cachekey));
    }

    public function testCacheKeyExists()
    {
        $cachedriver = m::mock('\Dbrouter\Cache\Driver\DatabaseDriver');
        $cachedriver->shouldReceive('exists')
                    ->once()
                    ->with('a94a8fe5ccb19ba61c4c0873d391e987982fbbd3')
                    ->andReturn(true);

        $cachekey = new CacheKey('test');

        $this->assertInstanceOf('\Dbrouter\Cache\CacheKey\CacheKey', $cachekey);
        $this->assertEquals('a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', $cachekey->getHash());
        $this->assertTrue($cachekey->exists($cachedriver));
    }
}