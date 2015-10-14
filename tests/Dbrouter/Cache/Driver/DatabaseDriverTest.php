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
        $db->shouldReceive('fetchColumn')->andReturn('test');
        $db->shouldReceive('executeQuery');
        $db->shouldReceive('executeUpdate')->andReturn(1);
        $db->shouldReceive('delete')->once();

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

    public function testRevalidate()
    {
        $driver = new DatabaseDriver(array('test'));
        $this->assertEquals(1, $driver->revalidate());
    }

    public function testGet()
    {
        $driver = new DatabaseDriver(array('test'));
        $this->assertEquals('test', $driver->get('test'));
    }

    public function testExists()
    {
        $driver = new DatabaseDriver(array('test'));
        $this->assertTrue($driver->exists('test'));
    }

    public function testPut()
    {
        $driver = new DatabaseDriver(array('test'));
        $driver->put('test', 'test', 60);

        $this->assertEquals('test', $driver->get('test'));
    }

    public function testForget()
    {
        $driver = new DatabaseDriver(array('test'));
        $driver->forget('test');
    }

    public function testFlush()
    {
        $driver = new DatabaseDriver(array('test'));
        $driver->flush();
    }

    public function testValidate()
    {
        $driver = new DatabaseDriver(array('test'));
        $this->assertFalse($driver->validate('test'));

        // Expired

        m::resetContainer();

        $db = m::mock('Doctrine\DBAL\Connection');
        $db->shouldReceive('quote');
        $db->shouldReceive('fetchColumn')->andReturn(date('Y-m-d H:i:s', strtotime('-1 hour')));

        $factory = m::mock('alias:Dbrouter\Database\ConnectionFactory');
        $factory->shouldReceive('make')->andReturn($db);

        $driver = new DatabaseDriver(array('test'));
        $this->assertFalse($driver->validate('test'));

        // Valid

        m::resetContainer();

        $db = m::mock('Doctrine\DBAL\Connection');
        $db->shouldReceive('quote');
        $db->shouldReceive('fetchColumn')->andReturn(date('Y-m-d H:i:s', strtotime('+1 hour')));

        $factory = m::mock('alias:Dbrouter\Database\ConnectionFactory');
        $factory->shouldReceive('make')->andReturn($db);

        $driver = new DatabaseDriver(array('test'));
        $this->assertTrue($driver->validate('test'));
    }

    public function testGetItemCount()
    {
        m::resetContainer();

        $db = m::mock('Doctrine\DBAL\Connection');
        $db->shouldReceive('quote');
        $db->shouldReceive('executeUpdate')->andReturn(1);
        $db->shouldReceive('fetchColumn')->andReturn(1);

        $factory = m::mock('alias:Dbrouter\Database\ConnectionFactory');
        $factory->shouldReceive('make')->andReturn($db);

        $driver = new DatabaseDriver(array('test'));
        $this->assertEquals(1, $driver->getItemCount());
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