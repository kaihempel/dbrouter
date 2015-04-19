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
class UrlSegmentItemTest extends PHPUnit_Framework_TestCase 
{
    private $url_id = NULL;
    
    public function setUp() {
        parent::setUp();
        
        $this->url_id = m::mock('Dbrouter\Url\UrlIdentifier');
    }
    
    public function testNewUrlSegmentItem() {
        
        $item = new UrlSegmentItem($this->url_id, 'test');
        
        $this->assertInstanceOf('Dbrouter\Url\Segment\UrlSegmentItem', $item);
    }
    
    /**
     * @expectedException Dbrouter\Exception\Url\UrlSegmentItemException
     * @expectedExceptionMessage No path value given!
     */
    public function testNewUrlSegmentItemException() {        
        $item = new UrlSegmentItem($this->url_id, NULL);
    }
    
    public function testGetId() {
        
        $item = new UrlSegmentItem($this->url_id, 'test');
        
        $this->assertInstanceOf('Dbrouter\Url\UrlIdentifier', $item->getUrlId());
    }
    
    public function testGetValue() {
        
        $item = new UrlSegmentItem($this->url_id, 'test');
        
        $this->assertEquals('test', $item->getValue());
    }
    
    public function testHasType() {
        
        $item = new UrlSegmentItem($this->url_id, 'test');
        
        $this->assertFalse($item->hasType());
    }
    
    public function testGetType() {
        
        $item = new UrlSegmentItem($this->url_id, 'test');
        
        $this->assertEmpty($item->getType());
    }
    
    public function testIsFirstItem() {
        
        $item = new UrlSegmentItem($this->url_id, 'test');
        
        $this->assertTrue($item->isFirstItem());
    }
    
    public function testIsLstItem() {
        
        $item = new UrlSegmentItem($this->url_id, 'test');
        
        $this->assertTrue($item->isLastItem());
    }
    
    public function testGetBelow() {
        
        $item = new UrlSegmentItem($this->url_id, 'test');
        
        $this->assertEmpty($item->getBelow());
    }
    
    public function testGetAbove() {
        
        $item = new UrlSegmentItem($this->url_id, 'test');
        
        $this->assertEmpty($item->getAbove());
    }
    
    public function testAttachSegmentItemAbove() {
        
        $above  = new UrlSegmentItem($this->url_id, 'bar');
        $item   = new UrlSegmentItem($this->url_id, 'foo');
        
        $item->attachSegmentItemAbove($above);
        
        $this->assertFalse($item->isLastItem());
        $this->assertTrue($item->isFirstItem());
        $this->assertInstanceOf('\Dbrouter\Url\Segment\UrlSegmentItem', $item->getAbove());
        $this->assertEmpty($item->getBelow());
    }
    
}
