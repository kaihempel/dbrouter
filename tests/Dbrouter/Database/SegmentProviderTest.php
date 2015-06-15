<?php namespace Dbrouter\Database;

use PHPUnit_Framework_TestCase;
use Mockery as m;

/**
 * Url segment dataprovider test
 *
 * @package    Dbrouter
 * @subpackage tests
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class SegmentProviderTest extends PHPUnit_Framework_TestCase
{
    protected $db = NULL;

    public function setUp()
    {
        $this->db = m::mock('Doctrine\DBAL\Connection');
        $this->db->shouldReceive('setFetchMode');
        $this->db->shouldReceive('setNestTransactionsWithSavepoints');
        $this->db->shouldReceive('beginTransaction');
        $this->db->shouldReceive('commit');
        $this->db->shouldReceive('quote');
        $this->db->shouldReceive('executeQuery');
        $this->db->shouldReceive('insert');
        $this->db->shouldReceive('lastInsertId')->andReturn(1);
    }

    public function testNewProvider()
    {
        $provider = new SegmentProvider($this->db);

        $this->assertInstanceOf('\Dbrouter\Database\SegmentProvider', $provider);
        $this->assertEmpty($provider->getUrlId());
        $this->assertEmpty($provider->getItems());
    }

    public function testNewProviderWithUrl()
    {
        // Create item mock

        $item = m::mock('Dbrouter\Url\Segment\UrlSegmentItem');
        $item->shouldReceive('isFirstItem')->once()->andReturn(true);
        $item->shouldReceive('getAbove')->once()->andReturnNull();

        // Create url mock

        $url = m::mock('Dbrouter\Url\Url');
        $url->shouldReceive('getId')->once()->andReturn(1);
        $url->shouldReceive('getSegmentcount')->once()->andReturn(1);
        $url->shouldReceive('getSegments')->once()->andReturn($item);

        // Test the provider

        $provider = new SegmentProvider($this->db, $url);

        $this->assertInstanceOf('\Dbrouter\Database\SegmentProvider', $provider);
        $this->assertEquals(1, $provider->getUrlId());
        $this->assertEquals($item, $provider->getItems());
    }

    public function testGetUrlIdFromItem()
    {

        // Create item mock

        $item = m::mock('Dbrouter\Url\Segment\UrlSegmentItem');
        $item->shouldReceive('isFirstItem')->once()->andReturn(true);
        $item->shouldReceive('getAbove')->once()->andReturnNull();
        $item->shouldReceive('getUrlId')->once()->andReturn(2);

        // Test the provider

        $provider = new SegmentProvider($this->db);
        $provider->setSegmentItem($item);

        $this->assertInstanceOf('\Dbrouter\Database\SegmentProvider', $provider);
        $this->assertEquals(2, $provider->getUrlId());
    }

    public function testSaveExistingSingleItem()
    {
        // Extend DB mock

        $row1 = new \stdClass();
        $row1->id   = 1;
        $row1->name = 'html';

	$row2 = new \stdClass();
        $row2->id   = 2;
        $row2->name = 'css';

        $row3 = new \stdClass();
        $row3->id   = 3;
        $row3->name = 'xml';

        $this->db->shouldReceive('fetchColumn')->andReturn(101);
        $this->db->shouldReceive('fetchAll')->andReturn(array($row1, $row2, $row3));

        // Create item mock

        $item = m::mock('Dbrouter\Url\Segment\UrlSegmentItem');
        $item->shouldReceive('isFirstItem')->once()->andReturn(true);
        $item->shouldReceive('isLastItem')->andReturn(true);
        $item->shouldReceive('getAbove')->once()->andReturnNull();
        $item->shouldReceive('getBelow')->andReturnNull();
        $item->shouldReceive('getValue')->andReturn('test');

        // Test the provider

        $provider = new SegmentProvider($this->db);
        $provider->setSegmentItem($item);

        $this->assertInstanceOf('\Dbrouter\Database\SegmentProvider', $provider);
        $this->assertInstanceOf('\Dbrouter\Database\SegmentProvider', $provider->save());
    }

    public function testSaveNewSingleItem()
    {
        // Extend DB mock

        $row1 = new \stdClass();
        $row1->id   = 1;
        $row1->name = 'html';

	$row2 = new \stdClass();
        $row2->id   = 2;
        $row2->name = 'css';

        $row3 = new \stdClass();
        $row3->id   = 3;
        $row3->name = 'xml';

        $this->db->shouldReceive('fetchColumn')->andReturn(false);
        $this->db->shouldReceive('fetchAll')->andReturn(array($row1, $row2, $row3));

        // Create item mock

        $item = m::mock('Dbrouter\Url\Segment\UrlSegmentItem');
        $item->shouldReceive('isFirstItem')->once()->andReturn(true);
        $item->shouldReceive('isLastItem')->andReturn(true);
        $item->shouldReceive('getAbove')->once()->andReturnNull();
        $item->shouldReceive('getBelow')->andReturnNull();
        $item->shouldReceive('getType')->andReturn('path');
        $item->shouldReceive('hasExtentsion')->andReturn(false);
        $item->shouldReceive('getValue')->andReturn('test2');

        // Test the provider

        $provider = new SegmentProvider($this->db);
        $provider->setSegmentItem($item);
        $provider->setPosition(1);

        $this->assertInstanceOf('\Dbrouter\Database\SegmentProvider', $provider);
        $this->assertInstanceOf('\Dbrouter\Database\SegmentProvider', $provider->save());
    }

    public function testSaveNewItemChain()
    {
        // Extend DB mock

        $row1 = new \stdClass();
        $row1->id   = 1;
        $row1->name = 'html';

	$row2 = new \stdClass();
        $row2->id   = 2;
        $row2->name = 'css';

        $row3 = new \stdClass();
        $row3->id   = 3;
        $row3->name = 'jpeg';

        $this->db->shouldReceive('fetchColumn')->andReturn(false);
        $this->db->shouldReceive('fetchAll')->andReturn(array($row1, $row2, $row3));
        $this->db->shouldReceive('lastInsertId')->andReturn(1, 2, 3);

        // Url identifier mock

        $urlId = m::mock('Dbrouter\Url\UrlIdentifier');
        $urlId->shouldReceive('getId')->andReturn(1);

        // Create item mock

        $item1Id = m::mock('Dbrouter\Url\Segment\UrlSegmentIdentifier');
        $item1Id->shouldReceive('getId')->andReturn(1);

        $item1 = m::mock('Dbrouter\Url\Segment\UrlSegmentItem');
        $item1->shouldReceive('getId')->once()->andReturn(null, $item1Id);
        $item1->shouldReceive('setId')->with(m::type('Dbrouter\Url\Segment\UrlSegmentIdentifier'))->once();
        $item1->shouldReceive('isFirstItem')->once()->andReturn(true);
        $item1->shouldReceive('isLastItem')->andReturn(false);
        $item1->shouldReceive('getType')->andReturn('path');
        $item1->shouldReceive('hasExtentsion')->andReturn(false);
        $item1->shouldReceive('getValue')->andReturn('test3');

        $item2Id = m::mock('Dbrouter\Url\Segment\UrlSegmentIdentifier');
        $item2Id->shouldReceive('getId')->andReturn(2);

        $item2 = m::mock('Dbrouter\Url\Segment\UrlSegmentItem');
        $item2->shouldReceive('getId')->once()->andReturn(null, $item2Id);
        $item2->shouldReceive('setId')->with(m::type('Dbrouter\Url\Segment\UrlSegmentIdentifier'))->once();
        $item2->shouldReceive('isFirstItem')->once()->andReturn(true);
        $item2->shouldReceive('isLastItem')->andReturn(false);
        $item2->shouldReceive('getType')->andReturn('path');
        $item2->shouldReceive('hasExtentsion')->andReturn(false);
        $item2->shouldReceive('getValue')->andReturn('path');

        $item3Id = m::mock('Dbrouter\Url\Segment\UrlSegmentIdentifier');
        $item3Id->shouldReceive('getId')->andReturn(3);

        $item3 = m::mock('Dbrouter\Url\Segment\UrlSegmentItem');
        $item3->shouldReceive('getId')->once()->andReturn(null, $item3Id);
        $item3->shouldReceive('setId')->with(m::type('Dbrouter\Url\Segment\UrlSegmentIdentifier'))->once();
        $item3->shouldReceive('isFirstItem')->once()->andReturn(true);
        $item3->shouldReceive('isLastItem')->andReturn(true);
        $item3->shouldReceive('getType')->andReturn('file');
        $item3->shouldReceive('hasExtentsion')->andReturn(true);
        $item3->shouldReceive('getExtentsion')->andReturn('jpeg');
        $item3->shouldReceive('getValue')->andReturn('test2.jpg');

        // Define chain

        $item1->shouldReceive('getAbove')->once()->andReturn($item2);
        $item1->shouldReceive('getBelow')->andReturnNull();
        $item2->shouldReceive('getAbove')->once()->andReturn($item3);
        $item2->shouldReceive('getBelow')->andReturn($item1);
        $item3->shouldReceive('getAbove')->once()->andReturnNull();
        $item3->shouldReceive('getBelow')->andReturn($item2);

        // First item has to return the corresponding URL ID

        $item1->shouldReceive('getUrlId')->once()->andReturn($urlId);

        // Test the provider

        $provider = new SegmentProvider($this->db);
        $provider->setSegmentItem($item1);

        $this->assertInstanceOf('\Dbrouter\Database\SegmentProvider', $provider);
        $this->assertInstanceOf('\Dbrouter\Database\SegmentProvider', $provider->save());
    }

    public function testLoadItem()
    {
        // Extend DB mock

        $row1 = new \stdClass();
        $row1->id   = 1;
        $row1->segment = 'test';

	$row2 = new \stdClass();
        $row2->id   = 2;
        $row2->segment = 'path';

        $row3 = new \stdClass();
        $row3->id   = 3;
        $row3->segment = 'file.txt';

        $stmt = m::mock('\Doctrine\DBAL\Driver\Statement');
        $stmt->shouldReceive('fetchAll')->andReturn(array($row1, $row2, $row3));

        $qb = m::mock('Doctrine\DBAL\Query\QueryBuilder');
        $qb->shouldReceive('select')->andReturnSelf();
        $qb->shouldReceive('from')->andReturnSelf();
        $qb->shouldReceive('leftJoin')->andReturnSelf();
        $qb->shouldReceive('where')->andReturnSelf();
        $qb->shouldReceive('orderBy')->andReturnSelf();
        $qb->shouldReceive('setParameter')->andReturnSelf();
        $qb->shouldReceive('execute')->andReturn($stmt);

        // Add querybuilder to db mock

        $this->db->shouldReceive('createQueryBuilder')->andReturn($qb);

        // Url identifier mock

        $urlId = m::mock('Dbrouter\Url\UrlIdentifier');
        $urlId->shouldReceive('getId')->andReturn(1);

        // Test the provider

        $provider = new SegmentProvider($this->db);
        $provider->load($urlId);

        $this->assertInstanceOf('\Dbrouter\Database\SegmentProvider', $provider);
        $this->assertInstanceOf('\Dbrouter\Url\Segment\UrlSegmentItem', $provider->getItems());
    }

    /**
     * @expectedException \Dbrouter\Exception\Database\DataProviderException
     */
    public function testSetPositionException() {
        $provider = new SegmentProvider($this->db);
        $provider->setPosition('test');
    }

    /**
     * @expectedException \Dbrouter\Exception\Database\DataProviderException
     * @expectedExceptionMessage No segment items set!
     */
    public function testSaveWithoutItemsException() {
        $provider = new SegmentProvider($this->db);
        $provider->save();
    }

    public function testDataProviderExists()
    {
        // Extend DB mock

        $this->db->shouldReceive('fetchColumn')->andReturn(101);

        // Use the segment provider to test the parent method

        $provider = new SegmentProvider($this->db);

        $this->assertEquals(101, $provider->exists('dbr_segment', 'name', 'test'));
    }
}