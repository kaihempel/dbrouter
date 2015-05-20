<?php namespace Dbrouter\Url\Segment;

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
class UrlSegmentItemFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Dbrouter\Exception\Url\UrlSegmentItemException
     * @expectedExceptionMessage Unexpected segment given!
     */
    public function testUrlSegmentItemException() {
        UrlSegmentItemFactory::make('');
    }

    public function testNewItem()
    {
        $item = UrlSegmentItemFactory::make('test');

        $this->assertInstanceOf('\Dbrouter\Url\Segment\UrlSegmentItem', $item);
        $this->assertEquals('test', $item->getValue());
    }

    public function testNewUrlIdItem()
    {
        $id = m::mock('\Dbrouter\Url\UrlIdentifier');
        $id->shouldReceive('getId')->andReturn(1);

        $item = UrlSegmentItemFactory::make('test', $id);

        $this->assertInstanceOf('\Dbrouter\Url\Segment\UrlSegmentItem', $item);
        $this->assertEquals('test', $item->getValue());
        $this->assertInstanceOf('\Dbrouter\Url\UrlIdentifier', $item->getUrlId());
        $this->assertEquals(1, $item->getUrlId()->getId());
        $this->assertEmpty($item->getId());
    }

    public function testNewSegmentIdItem()
    {
        $id = m::mock('\Dbrouter\Url\Segment\UrlSegmentIdentifier');
        $id->shouldReceive('getId')->andReturn(1);

        $item = UrlSegmentItemFactory::make('test', NULL, $id);

        $this->assertInstanceOf('\Dbrouter\Url\Segment\UrlSegmentItem', $item);
        $this->assertEquals('test', $item->getValue());
        $this->assertEmpty($item->getUrlId());
        $this->assertInstanceOf('\Dbrouter\Url\Segment\UrlSegmentIdentifier', $item->getId());
        $this->assertEquals(1, $item->getId()->getId());
    }

    public function testNewItemWithAllIds()
    {
        $urlId = m::mock('\Dbrouter\Url\UrlIdentifier');
        $urlId->shouldReceive('getId')->andReturn(1);

        $id = m::mock('\Dbrouter\Url\Segment\UrlSegmentIdentifier');
        $id->shouldReceive('getId')->andReturn(1);

        $item = UrlSegmentItemFactory::make('test', $urlId, $id);

        $this->assertInstanceOf('\Dbrouter\Url\Segment\UrlSegmentItem', $item);
        $this->assertEquals('test', $item->getValue());
        $this->assertInstanceOf('\Dbrouter\Url\UrlIdentifier', $item->getUrlId());
        $this->assertEquals(1, $item->getUrlId()->getId());
        $this->assertInstanceOf('\Dbrouter\Url\Segment\UrlSegmentIdentifier', $item->getId());
        $this->assertEquals(1, $item->getId()->getId());
    }
}