<?php namespace Dbrouter\Database\Mapper;

use PHPUnit_Framework_TestCase;
use Mockery as m;

/**
 * Url segment parser item test
 *
 * @package    Dbrouter
 * @subpackage tests
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class PlaceholderTypeMapperTest extends PHPUnit_Framework_TestCase
{
    protected $db = NULL;

    public function setUp()
    {
        $row1 = new \stdClass();
        $row1->id       = 1;
        $row1->type     = 'integer';
        $row1->regex    = '^[0-9]+$';

	$row2 = new \stdClass();
        $row2->id       = 2;
        $row2->type     = 'id';
        $row2->regex    = '^[1-9]{1,10}$';

        $row3 = new \stdClass();
        $row3->id       = 3;
        $row3->type     = 'string';
        $row3->regex    = '^.*$';

        $this->db = m::mock('Doctrine\DBAL\Connection');
        $this->db->shouldReceive('setFetchMode');
        $this->db->shouldReceive('fetchAll')->andReturn(array($row1, $row2, $row3));
    }

    /**
     * @expectedException Dbrouter\Exception\Database\MapperException
     */
    public function testLoadException()
    {
        $db = m::mock('Doctrine\DBAL\Connection');
        $db->shouldReceive('setFetchMode');
        $db->shouldReceive('fetchAll')->andReturn(array());

        $mapper = new PlaceholderTypeMapper($db);
    }

    public function testNewMapper()
    {
        $mapper = new PlaceholderTypeMapper($this->db);

        $this->assertInstanceOf('Dbrouter\Database\Mapper\PlaceholderTypeMapper', $mapper);
        $this->assertEquals(1, $mapper->getValue('integer'));
        $this->assertEquals(2, $mapper->getValue('id'));
        $this->assertEquals(3, $mapper->getValue('string'));

        // test not mapped
        $this->assertEmpty($mapper->getValue('test'));
    }

    public function testGetTypeID()
    {
        $item = m::mock('Dbrouter\Url\Segment\Placeholder');
        $item->shouldReceive('getType')->once()->andReturn('integer');

        $mapper = new PlaceholderTypeMapper($this->db);

        $this->assertInstanceOf('Dbrouter\Database\Mapper\PlaceholderTypeMapper', $mapper);
        $this->assertEquals(1, $mapper->getPlaceholderTypeId($item->getType()));

    }

}