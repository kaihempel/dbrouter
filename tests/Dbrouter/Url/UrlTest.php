<?php namespace Dbrouter\Url;

use PHPUnit_Framework_TestCase;
use Mockery as m;

/**
 * Url path item test
 *
 * @package    Dbrouter
 * @subpackage tests
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class UrlTest extends PHPUnit_Framework_TestCase
{

    protected $testurl = '/test/this/path/';

    public function setUp()
    {
        parent::setUp();
    }

    public function testNewUrl()
    {
        $url = new Url($this->testurl);

        $this->assertInstanceOf('Dbrouter\Url\Url', $url);
        $this->assertFalse($url->hasId());
        $this->assertEmpty($url->getId());
        $this->assertEquals('/test/this/path/', $url->getRawUrl());
        $this->assertEmpty($url->getSegments());
        $this->assertEquals(0, $url->getSegmentcount());
        $this->assertEquals(0, $url->getWeight());
    }

    /**
     * @expectedException Dbrouter\Exception\Url\UrlException
     */
    public function testNewUrlException()
    {
        $url = new Url('');
    }

    public function testParse()
    {
        $url = new Url($this->testurl);
        $url->parse();

        $this->assertInstanceOf('Dbrouter\Url\Url', $url);
        $this->assertFalse($url->hasId());
        $this->assertEmpty($url->getId());
        $this->assertEquals('/test/this/path/', $url->getRawUrl());
        $this->assertInstanceOf('Dbrouter\Url\Segment\UrlSegmentItem', $url->getSegments());
        $this->assertEquals(3, $url->getSegmentcount());
        $this->assertEquals(6, $url->getWeight());
    }

    public function testSetId()
    {
        $urlId = m::mock('Dbrouter\Url\UrlIdentifier');
        $urlId->shouldReceive('getId')->andReturn(1);

        $url = new Url($this->testurl);
        $url->setId($urlId);

        $this->assertInstanceOf('Dbrouter\Url\Url', $url);
        $this->assertTrue($url->hasId());
        $this->assertInstanceOf('Dbrouter\Url\UrlIdentifier', $url->getId());
        $this->assertEquals(1, $url->getId()->getId());
    }

    /**
     * @expectedException \Dbrouter\Exception\Url\UrlException
     */
    public function testSetIdException()
    {
        $urlId1 = m::mock('Dbrouter\Url\UrlIdentifier');
        $urlId1->shouldReceive('getId')->andReturn(1);

        $urlId2 = m::mock('Dbrouter\Url\UrlIdentifier');
        $urlId2->shouldReceive('getId')->andReturn(2);

        $url = new Url($this->testurl);
        $url->setId($urlId1);
        $url->setId($urlId2);

    }

    public function testAttachUrlSegmentItem()
    {
        $url = new Url($this->testurl);

        $this->assertInstanceOf('Dbrouter\Url\Url', $url);
        $this->assertFalse($url->hasId());
        $this->assertEmpty($url->getId());
        $this->assertEquals('/test/this/path/', $url->getRawUrl());
        $this->assertEmpty($url->getSegments());
        $this->assertEquals(0, $url->getSegmentcount());
        $this->assertEquals(0, $url->getWeight());

        // First item

        $item1 = m::mock('\Dbrouter\Url\Segment\UrlSegmentItem');
        $item1->shouldReceive('getWeight')->once()->andReturn(2);
        $item1->shouldReceive('getType')->once()->andReturn('path');
        $item1->shouldReceive('isLastItem')->once()->andReturn(true);
        $item1->shouldReceive('attachSegmentItemBelow');
        $item1->shouldReceive('attachSegmentItemAbove');

        $url->attachUrlSegmentItem($item1);

        // Attach below

        $item2 = m::mock('\Dbrouter\Url\Segment\UrlSegmentItem');
        $item2->shouldReceive('getWeight')->once()->andReturn(2);
        $item2->shouldReceive('getType')->once()->andReturn('path');
        $item2->shouldReceive('isLastItem')->once()->andReturn(false);
        $item2->shouldReceive('getAbove')->andReturn($item1);
        $item2->shouldReceive('attachSegmentItemAbove');

        $url->attachUrlSegmentItem($item2);

        // Attach above

        $item3 = m::mock('\Dbrouter\Url\Segment\UrlSegmentItem');
        $item3->shouldReceive('getWeight')->once()->andReturn(2);
        $item3->shouldReceive('getType')->once()->andReturn('path');

        $url->attachUrlSegmentItem($item3, \Dbrouter\Url\Segment\UrlSegmentItem::ATTACH_MODE_ABOVE);
    }

    /**
     * @expectedException \Dbrouter\Exception\Url\UrlException
     */
    public function testAttachEmpty()
    {
        $item = m::mock('\Dbrouter\Url\Segment\UrlSegmentItem');

        $url = new Url($this->testurl);
        $url->attachUrlSegmentItem($item, NULL);
    }

}
