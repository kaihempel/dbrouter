<?php  namespace Dbrouter\Database\Provider;

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
        $provider = new UrlProvider($this->db);

        $this->assertInstanceOf('\Dbrouter\Database\Provider\UrlProvider', $provider);
        $this->assertEmpty($provider->getUrlId());
        $this->assertEmpty($provider->getUrl());
    }

    public function testNewProviderWithUrl()
    {
        // Create item mock

        $item = m::mock('Dbrouter\Url\Segment\UrlSegmentItem');
        $item->shouldReceive('isFirstItem')->once()->andReturn(true);
        $item->shouldReceive('getAbove')->once()->andReturnNull();

        // Create url id mock

        $urlId = m::mock('Dbrouter\Url\UrlIdentifier');
        $urlId->shouldReceive('getId')->once()->andReturn(1);

        // Create url mock

        $url = m::mock('Dbrouter\Url\Url');
        $url->shouldReceive('getId')->once()->andReturn($urlId);
        $url->shouldReceive('getSegmentcount')->once()->andReturn(1);
        $url->shouldReceive('getSegments')->once()->andReturn($item);

        // Test the provider

        $provider = new UrlProvider($this->db, $url);

        $this->assertInstanceOf('\Dbrouter\Database\Provider\UrlProvider', $provider);
        $this->assertInstanceOf('\Dbrouter\Url\UrlIdentifier', $provider->getUrlId());
        $this->assertEquals(1, $provider->getUrlId()->getId());
        $this->assertEquals($url, $provider->getUrl());
    }

    public function testSetUrl()
    {
        // Create item mock

        $item = m::mock('Dbrouter\Url\Segment\UrlSegmentItem');
        $item->shouldReceive('isFirstItem')->once()->andReturn(true);
        $item->shouldReceive('getAbove')->once()->andReturnNull();

        // Create url id mock

        $urlId = m::mock('Dbrouter\Url\UrlIdentifier');
        $urlId->shouldReceive('getId')->once()->andReturn(1);

        // Create url mock

        $url = m::mock('Dbrouter\Url\Url');
        $url->shouldReceive('getId')->once()->andReturn($urlId);
        $url->shouldReceive('getSegmentcount')->once()->andReturn(1);
        $url->shouldReceive('getSegments')->once()->andReturn($item);

        // Test the provider

        $provider = new UrlProvider($this->db);
        $provider->setUrl($url);

        $this->assertInstanceOf('\Dbrouter\Database\Provider\UrlProvider', $provider);
        $this->assertInstanceOf('\Dbrouter\Url\UrlIdentifier', $provider->getUrlId());
        $this->assertEquals(1, $provider->getUrlId()->getId());
        $this->assertEquals($url, $provider->getUrl());
    }

    public function testSaveUrl()
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

        // Query Builder

        $qb = m::mock('Doctrine\DBAL\Query\QueryBuilder');
        $qb->shouldReceive('select')->andReturnSelf();
        $qb->shouldReceive('from')->andReturnSelf();
        $qb->shouldReceive('join')->andReturnSelf();
        $qb->shouldReceive('leftJoin')->andReturnSelf();
        $qb->shouldReceive('where')->andReturnSelf();
        $qb->shouldReceive('andWhere')->andReturnSelf();
        $qb->shouldReceive('orderBy')->andReturnSelf();
        $qb->shouldReceive('groupBy')->andReturnSelf();
        $qb->shouldReceive('setParameter')->andReturnSelf();

        // Add querybuilder to db mock

        $this->db->shouldReceive('createQueryBuilder')->andReturn($qb);

        $stmt = m::mock('\Doctrine\DBAL\Driver\Statement');
        $stmt->shouldReceive('fetchAll')->andReturn(array());

        $qb->shouldReceive('execute')->andReturn($stmt);


        // Create item mock

        $item = m::mock('Dbrouter\Url\Segment\UrlSegmentItem');
        $item->shouldReceive('isFirstItem')->once()->andReturn(true);
        $item->shouldReceive('getAbove')->once()->andReturnNull();
        $item->shouldReceive('getValue')->once()->andReturn('test');
        $item->shouldReceive('getType')->andReturn('path');
        $item->shouldReceive('hasExtentsion')->andReturn(false);

        // Url ID mock

        $urlId = m::mock('Dbrouter\Url\UrlIdentifier');
        $urlId->shouldReceive('getId')->once()->andReturn(1);

        // Create url mock

        $url = m::mock('Dbrouter\Url\Url');
        $url->shouldReceive('getId')->twice()->andReturn($urlId);
        $url->shouldReceive('setId')->once();
        $url->shouldReceive('hasId')->once()->andReturn(false);
        $url->shouldReceive('getSegmentcount')->once()->andReturn(1);
        $url->shouldReceive('getWeight')->once()->andReturn(3);
        $url->shouldReceive('usesPlaceholder')->once()->andReturn(false);
        $url->shouldReceive('usesWildcard')->once()->andReturn(false);
        $url->shouldReceive('getSegments')->once()->andReturn($item);

        // Test the provider

        $provider = new UrlProvider($this->db, $url);
        $provider->save();

        $this->assertInstanceOf('\Dbrouter\Database\Provider\UrlProvider', $provider);
        $this->assertInstanceOf('\Dbrouter\Url\UrlIdentifier', $provider->getUrlId());
        $this->assertEquals(1, $provider->getUrlId()->getId());
        $this->assertEquals($url, $provider->getUrl());
    }

    /**
     * @expectedException \Dbrouter\Exception\Database\DataProviderException
     * @expectedExceptionMessage No URL instance set!
     */
    public function testSaveUrlException()
    {
        $provider = new UrlProvider($this->db);
        $provider->save();
    }

    public function testLoad()
    {
        // Extend DB mock

        $data = array();
        $data['segmentcount'] = 3;
        $data['weight'] = 9;
        $data['uses_placeholder'] = 0;
        $data['uses_wildcard'] = 0;

        $this->db->shouldReceive('fetchAssoc')->andReturn($data);

        // Extend DB mock for the segment provider
        //
        // The segment provider loads the items in descending order.
        // This depends on the position value from the mapping table.

        $row3 = new \stdClass();
        $row3->id   = 3;
        $row3->segment = 'file.txt';

        $row2 = new \stdClass();
        $row2->id   = 2;
        $row2->segment = 'path';

        $row1 = new \stdClass();
        $row1->id   = 1;
        $row1->segment = 'test';

        $stmt = m::mock('\Doctrine\DBAL\Driver\Statement');
        $stmt->shouldReceive('fetchAll')->andReturn(array($row3, $row2, $row1));

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

        $provider = new UrlProvider($this->db);
        $provider->load($urlId);

        $this->assertInstanceOf('Dbrouter\Database\Provider\UrlProvider', $provider);
        $this->assertInstanceOf('Dbrouter\Url\Url', $provider->getUrl());
        $this->assertEquals('/test/path/file.txt', $provider->getUrl()->getRawUrl());
    }

    /**
     * @expectedException \Dbrouter\Exception\Database\DataProviderException
     * @expectedExceptionMessage No items could be loaded!
     */
    public function testItemLoadException()
    {
        // Extend DB mock

        $this->db->shouldReceive('fetchAssoc')->andReturnNull();

        $stmt = m::mock('\Doctrine\DBAL\Driver\Statement');
        $stmt->shouldReceive('fetchAll')->andReturnNull();

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

        $provider = new UrlProvider($this->db);
        $provider->load($urlId);
    }

    /**
     * @expectedException \Dbrouter\Exception\Database\DataProviderException
     * @expectedExceptionMessage Url doesn't exists!
     */
    public function testUrlLoadException()
    {
        // Extend DB mock

        $this->db->shouldReceive('fetchAssoc')->andReturnNull();

        // Extend DB mock for the segment provider
        //
        // The segment provider loads the items in descending order.
        // This depends on the position value from the mapping table.

        $row3 = new \stdClass();
        $row3->id   = 3;
        $row3->segment = 'file.txt';

        $row2 = new \stdClass();
        $row2->id   = 2;
        $row2->segment = 'path';

        $row1 = new \stdClass();
        $row1->id   = 1;
        $row1->segment = 'test';

        $stmt = m::mock('\Doctrine\DBAL\Driver\Statement');
        $stmt->shouldReceive('fetchAll')->andReturn(array($row3, $row2, $row1));

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

        $provider = new UrlProvider($this->db);
        $provider->load($urlId);
    }
}