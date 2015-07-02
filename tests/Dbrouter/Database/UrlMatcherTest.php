<?php namespace Dbrouter\Database;

use PHPUnit_Framework_TestCase;
use Mockery as m;

/**
 * Url matcher test
 *
 * @package    Dbrouter
 * @subpackage tests
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class UrlMatcherTest extends PHPUnit_Framework_TestCase
{
    protected $db = null;
    protected $qb = null;

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

        $this->qb = m::mock('Doctrine\DBAL\Query\QueryBuilder');
        $this->qb->shouldReceive('select')->andReturnSelf();
        $this->qb->shouldReceive('from')->andReturnSelf();
        $this->qb->shouldReceive('join')->andReturnSelf();
        $this->qb->shouldReceive('leftJoin')->andReturnSelf();
        $this->qb->shouldReceive('where')->andReturnSelf();
        $this->qb->shouldReceive('andWhere')->andReturnSelf();
        $this->qb->shouldReceive('orderBy')->andReturnSelf();
        $this->qb->shouldReceive('groupBy')->andReturnSelf();
        $this->qb->shouldReceive('setParameter')->andReturnSelf();

        // Add querybuilder to db mock

        $this->db->shouldReceive('createQueryBuilder')->andReturn($this->qb);
    }

    public function testNewMatcher()
    {
        // Add one simple item mock

        $item = m::mock('Dbrouter\Url\Segment\UrlSegmentItem');
        $item->shouldReceive('isFirstItem')->once()->andReturn(true);
        $item->shouldReceive('isLastItem')->once()->andReturn(true);
        $item->shouldReceive('getAbove')->once()->andReturnNull();
        $item->shouldReceive('getValue')->once()->andReturn('test');
        $item->shouldReceive('getType')->andReturn('path');
        $item->shouldReceive('hasExtentsion')->andReturn(false);

        // Url mock for the URL "/test/"

        $url = m::mock('Dbrouter\Url\Url');
        $url->shouldReceive('parse')->once()->andReturnSelf();
        $url->shouldReceive('getSegments')->twice()->andReturn(null, $item);

        // Extend DB mock

        $stmt = m::mock('\Doctrine\DBAL\Driver\Statement');
        $stmt->shouldReceive('fetchAll')->andReturn(array());

        $this->qb->shouldReceive('execute')->andReturn($stmt);

        // Test the matcher

        $matcher = new UrlMatcher($this->db, $url);

        $this->assertInstanceOf('\Dbrouter\Database\UrlMatcher', $matcher);
        $this->assertFalse($matcher->isMatch());
        $this->assertEmpty($matcher->getMatchCount());
        $this->assertInternalType('array', $matcher->getAllMatches());
        $this->assertEmpty($matcher->getAllMatches());
        $this->assertEmpty($matcher->getBestMatch());
    }

    /**
     * @expectedException Dbrouter\Exception\Database\UrlMatcherException
     * @expectedExceptionMessage No segments!
     */
    public function testSegmentException()
    {
        // Empty url mock

        $url = m::mock('Dbrouter\Url\Url');
        $url->shouldReceive('parse')->once()->andReturnSelf();
        $url->shouldReceive('getSegments')->twice()->andReturnNull();

        // Extend DB mock

        $stmt = m::mock('\Doctrine\DBAL\Driver\Statement');
        $stmt->shouldReceive('fetchAll')->andReturn(array());

        $this->qb->shouldReceive('execute')->andReturn($stmt);

        // Test the matcher

        $matcher = new UrlMatcher($this->db, $url);

        $this->assertInstanceOf('\Dbrouter\Database\UrlMatcher', $matcher);
        $this->assertFalse($matcher->isMatch());
        $this->assertEmpty($matcher->getMatchCount());
        $this->assertInternalType('array', $matcher->getAllMatches());
        $this->assertEmpty($matcher->getAllMatches());
        $this->assertEmpty($matcher->getBestMatch());
    }

    public function testSuccessfullMatch()
    {
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

        // Url mock for the URL "/test/"

        $url = m::mock('Dbrouter\Url\Url');
        $url->shouldReceive('parse')->once()->andReturnSelf();
        $url->shouldReceive('getSegments')->once()->andReturn($item1);

        // Extend DB mock

        $result1 = new \stdClass();
        $result1->id = 1;

        $result2 = new \stdClass();
        $result2->id = 2;

        $stmt = m::mock('\Doctrine\DBAL\Driver\Statement');
        $stmt->shouldReceive('fetchAll')->andReturn(array($result1, $result2));

        $this->qb->shouldReceive('execute')->andReturn($stmt);

        // Test the matcher

        $matcher = new UrlMatcher($this->db, $url);

        $this->assertInstanceOf('\Dbrouter\Database\UrlMatcher', $matcher);
        $this->assertTrue($matcher->isMatch());
        $this->assertEquals(2, $matcher->getMatchCount());
        $this->assertInternalType('array', $matcher->getAllMatches());
        $this->assertInstanceOf('\stdClass', $matcher->getBestMatch());
        $this->assertEquals(1, $matcher->getBestMatch()->id);
    }

    public function testMatchWithChainReverse()
    {
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
        $item2->shouldReceive('isFirstItem')->once()->andReturn(false);
        $item2->shouldReceive('isLastItem')->andReturn(false);
        $item2->shouldReceive('getType')->andReturn('path');
        $item2->shouldReceive('hasExtentsion')->andReturn(false);
        $item2->shouldReceive('getValue')->andReturn('path');

        $item3Id = m::mock('Dbrouter\Url\Segment\UrlSegmentIdentifier');
        $item3Id->shouldReceive('getId')->andReturn(3);

        $item3 = m::mock('Dbrouter\Url\Segment\UrlSegmentItem');
        $item3->shouldReceive('getId')->once()->andReturn(null, $item3Id);
        $item3->shouldReceive('setId')->with(m::type('Dbrouter\Url\Segment\UrlSegmentIdentifier'))->once();
        $item3->shouldReceive('isFirstItem')->once()->andReturn(false);
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

        // Url mock for the URL "/test/"

        $url = m::mock('Dbrouter\Url\Url');
        $url->shouldReceive('parse')->once()->andReturnSelf();
        $url->shouldReceive('getSegments')->once()->andReturn($item3);

        // Extend DB mock

        $result1 = new \stdClass();
        $result1->id = 1;

        $result2 = new \stdClass();
        $result2->id = 2;

        $stmt = m::mock('\Doctrine\DBAL\Driver\Statement');
        $stmt->shouldReceive('fetchAll')->andReturn(array($result1, $result2));

        $this->qb->shouldReceive('execute')->andReturn($stmt);

        // Test the matcher

        $matcher = new UrlMatcher($this->db, $url);

        $this->assertInstanceOf('\Dbrouter\Database\UrlMatcher', $matcher);
        $this->assertTrue($matcher->isMatch());
        $this->assertEquals(2, $matcher->getMatchCount());
        $this->assertInternalType('array', $matcher->getAllMatches());
        $this->assertInstanceOf('\stdClass', $matcher->getBestMatch());
        $this->assertEquals(1, $matcher->getBestMatch()->id);
    }
}