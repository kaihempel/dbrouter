<?php  namespace Dbrouter\Database\Mapper;

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
class ExtentsionMapperTest extends PHPUnit_Framework_TestCase
{
    protected $db = NULL;

    public function setUp()
    {
        $row1 = new \stdClass();
        $row1->id   = 1;
        $row1->name = 'html';

	$row2 = new \stdClass();
        $row2->id   = 2;
        $row2->name = 'css';

        $row3 = new \stdClass();
        $row3->id   = 3;
        $row3->name = 'xml';

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

        $mapper = new ExtentsionMapper($db);
    }

    public function testNewMapper()
    {
        $mapper = new ExtentsionMapper($this->db);

        $this->assertInstanceOf('Dbrouter\Database\Mapper\ExtentsionMapper', $mapper);
        $this->assertEquals(1, $mapper->getValue('html'));
        $this->assertEquals(2, $mapper->getValue('css'));
        $this->assertEquals(3, $mapper->getValue('xml'));

        // Placeholder not mapped
        $this->assertEmpty($mapper->getValue('jpeg'));
    }

    public function testCachedData()
    {
        // First mapper instance

        $mapper1 = new ExtentsionMapper($this->db);

        $this->assertInstanceOf('Dbrouter\Database\Mapper\ExtentsionMapper', $mapper1);
        $this->assertEquals(1, $mapper1->getValue('html'));
        $this->assertEmpty($mapper1->getValue('jpeg'));

        // Second mapper instance

        $row = new \stdClass();
        $row->id   = 1;
        $row->name = 'svg';

        $db = m::mock('Doctrine\DBAL\Connection');
        $db->shouldReceive('setFetchMode');
        $db->shouldReceive('fetchAll')->andReturn(array($row));

        $mapper2 = new ExtentsionMapper($db);

        $this->assertInstanceOf('Dbrouter\Database\Mapper\ExtentsionMapper', $mapper2);
        $this->assertEquals(1, $mapper2->getValue('html'));
        $this->assertEmpty($mapper2->getValue('jpeg'));
    }

    public function testGetTypeID()
    {
        $item = m::mock('Dbrouter\Url\Segment\UrlSegmentItem');
        $item->shouldReceive('getExtentsion')->once()->andReturn('html');

        $mapper = new ExtentsionMapper($this->db);

        $this->assertInstanceOf('Dbrouter\Database\Mapper\ExtentsionMapper', $mapper);
        $this->assertEquals(1, $mapper->getExtentsionId($item));

    }
}
