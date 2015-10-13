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
    public function setUp()
    {
        $db = m::mock('Doctrine\DBAL\Connection');
        $db->shouldReceive('setFetchMode');
        $db->shouldReceive('setNestTransactionsWithSavepoints');
        $db->shouldReceive('beginTransaction');
        $db->shouldReceive('commit');
        $db->shouldReceive('quote');
        $db->shouldReceive('fetchColumn')->andReturn(array('table' => 'test'));

        $factory = m::mock('alias:Dbrouter\Database\ConnectionFactory');
        $factory->shouldReceive('make')->andReturn($db);
    }

    public function testNewDriver()
    {
        $driver = new DatabaseDriver(array('test'));
    }

    public function testSetTable()
    {
        $driver = new DatabaseDriver(array('test'), 'test');
    }

    // Exception tests

    /**
     * @expectedException Dbrouter\Exception\Cache\DriverException
     */
    public function testNewDriverNoConnection()
    {
        m::resetContainer();

        $factory = m::mock('alias:Dbrouter\Database\ConnectionFactory');
        $factory->shouldReceive('make')->andThrow(\Dbrouter\Exception\Database\ConnectionFactoryException::make('No connection parameter given!'));

        $driver = new DatabaseDriver(array('test'));
    }

    /**
     * @expectedException Dbrouter\Exception\Cache\DriverException
     */
    public function testSetNonExistingTable()
    {
        m::resetContainer();

        $db = m::mock('Doctrine\DBAL\Connection');
        $db->shouldReceive('fetchColumn')->andReturnNull();

        $factory = m::mock('alias:Dbrouter\Database\ConnectionFactory');
        $factory->shouldReceive('make')->andReturn($db);

        $driver = new DatabaseDriver(array('test'));
        $driver->setTable('test');
    }
}