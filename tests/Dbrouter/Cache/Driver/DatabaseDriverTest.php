<?php  namespace Dbrouter\Cache\Driver;

use PHPUnit_Framework_TestCase;
use Mockery as m;

/**
 * Url dataprovider test
 *
 * @package    Dbrouter
 * @subpackage tests
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class UrlProviderTest extends PHPUnit_Framework_TestCase
{
    public function testNewDriver()
    {
        $driver = new DatabaseDriver();
    }
}