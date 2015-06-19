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
class UrlSegmentMergerTest extends PHPUnit_Framework_TestCase
{
    public function testNewMerger()
    {
        $merger = new UrlSegmentMerger();

        $this->assertInstanceOf('Dbrouter\Url\Segment\UrlSegmentMerger', $merger);
    }

    public function testMergeSingleItem()
    {
        $item = m::mock('Dbrouter\Url\Segment\UrlSegmentItem');
        $item->shouldReceive('isFirstItem')->once()->andReturn(true);
        $item->shouldReceive('isLastItem')->once()->andReturn(true);
        $item->shouldReceive('getValue')->once()->andReturn('test');
        $item->shouldReceive('getType')->andReturn('path');

        $merger = new UrlSegmentMerger();
        $merger->merge($item);

        $this->assertInstanceOf('Dbrouter\Url\Segment\UrlSegmentMerger', $merger);
        $this->assertEquals('/test', $merger->getUrl());
    }

    public function testMergeItemChain()
    {
        $item3 = m::mock('Dbrouter\Url\Segment\UrlSegmentItem');
        $item3->shouldReceive('isFirstItem')->once()->andReturn(false);
        $item3->shouldReceive('isLastItem')->once()->andReturn(true);
        $item3->shouldReceive('getValue')->once()->andReturn('top');
        $item3->shouldReceive('getType')->andReturn('path');

        $item2 = m::mock('Dbrouter\Url\Segment\UrlSegmentItem');
        $item2->shouldReceive('isFirstItem')->once()->andReturn(false);
        $item2->shouldReceive('isLastItem')->once()->andReturn(false);
        $item2->shouldReceive('getValue')->once()->andReturn('middle');
        $item2->shouldReceive('getType')->andReturn('path');
        $item2->shouldReceive('getAbove')->once()->andReturn($item3);

        $item1 = m::mock('Dbrouter\Url\Segment\UrlSegmentItem');
        $item1->shouldReceive('isFirstItem')->once()->andReturn(true);
        $item1->shouldReceive('isLastItem')->once()->andReturn(false);
        $item1->shouldReceive('getValue')->once()->andReturn('root');
        $item1->shouldReceive('getType')->andReturn('path');
        $item1->shouldReceive('getAbove')->once()->andReturn($item2);

        $merger = new UrlSegmentMerger();
        $merger->merge($item1);

        $this->assertInstanceOf('Dbrouter\Url\Segment\UrlSegmentMerger', $merger);
        $this->assertEquals('/root/middle/top', $merger->getUrl());
    }

    public function testRewindItemChain()
    {
        $item3 = m::mock('Dbrouter\Url\Segment\UrlSegmentItem');
        $item3->shouldReceive('isFirstItem')->once()->andReturn(false);
        $item3->shouldReceive('isLastItem')->once()->andReturn(true);
        $item3->shouldReceive('getValue')->once()->andReturn('top');
        $item3->shouldReceive('getType')->andReturn('path');

        $item2 = m::mock('Dbrouter\Url\Segment\UrlSegmentItem');
        $item2->shouldReceive('isFirstItem')->once()->andReturn(false);
        $item2->shouldReceive('isLastItem')->once()->andReturn(false);
        $item2->shouldReceive('getValue')->once()->andReturn('middle');
        $item2->shouldReceive('getType')->andReturn('path');
        $item2->shouldReceive('getAbove')->once()->andReturn($item3);

        $item1 = m::mock('Dbrouter\Url\Segment\UrlSegmentItem');
        $item1->shouldReceive('isFirstItem')->once()->andReturn(true);
        $item1->shouldReceive('isLastItem')->once()->andReturn(false);
        $item1->shouldReceive('getValue')->once()->andReturn('root');
        $item1->shouldReceive('getType')->andReturn('path');
        $item1->shouldReceive('getAbove')->once()->andReturn($item2);

        // Define below methods for rewind

        $item3->shouldReceive('getBelow')->once()->andReturn($item2);
        $item2->shouldReceive('getBelow')->once()->andReturn($item1);

        $merger = new UrlSegmentMerger();
        $merger->merge($item3);

        $this->assertInstanceOf('Dbrouter\Url\Segment\UrlSegmentMerger', $merger);
        $this->assertEquals('/root/middle/top', $merger->getUrl());
    }

    /**
     * @expectedException \Dbrouter\Exception\Url\UrlException
     */
    public function testRewindItemChainException()
    {
        $item2 = m::mock('Dbrouter\Url\Segment\UrlSegmentItem');
        $item2->shouldReceive('isFirstItem')->once()->andReturn(false);
        $item2->shouldReceive('isLastItem')->once()->andReturn(false);
        $item2->shouldReceive('getValue')->once()->andReturn('test');

        $item1 = m::mock('Dbrouter\Url\Segment\UrlSegmentItem');
        $item1->shouldReceive('isFirstItem')->once()->andReturn(false);
        $item1->shouldReceive('isLastItem')->once()->andReturn(false);
        $item1->shouldReceive('getValue')->once()->andReturn('test2');

        // Define item getter for infinity loop
        $item1->shouldReceive('getAbove')->once()->andReturn($item2);
        $item2->shouldReceive('getAbove')->once()->andReturn($item1);

        $item1->shouldReceive('getBelow')->once()->andReturn($item2);
        $item2->shouldReceive('getBelow')->once()->andReturn($item1);

        $merger = new UrlSegmentMerger();
        $merger->merge($item1);
    }
}