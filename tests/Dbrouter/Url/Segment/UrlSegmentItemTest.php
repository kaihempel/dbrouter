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
        
        $item = new UrlSegmentItem('test');
        
        $this->assertInstanceOf('Dbrouter\Url\Segment\UrlSegmentItem', $item);
    }
    
    /**
     * @expectedException Dbrouter\Exception\Url\UrlSegmentItemException
     * @expectedExceptionMessage No path value given!
     */
    public function testNewUrlSegmentItemException() {        
        $item = new UrlSegmentItem(NULL);
    }
    
    public function testUrlId() {
        
        $item = new UrlSegmentItem('test');
        $item->setId($this->url_id);
        
        $this->assertInstanceOf('Dbrouter\Url\UrlIdentifier', $item->getUrlId());
    }

    public function testGetValue() {
        
        $item = new UrlSegmentItem('test', $this->url_id);
        
        $this->assertEquals('test', $item->getValue());
    }
    
    public function testHasExtentsion() {
        
        $item = new UrlSegmentItem('test', $this->url_id);
        
        $this->assertFalse($item->hasExtentsion());
    }
    
    public function testGetExtentsion() {
        
        $item = new UrlSegmentItem('test', $this->url_id);
        
        $this->assertEmpty($item->getExtentsion());
    }
    
    public function testIsFirstItem() {
        
        $item = new UrlSegmentItem('test', $this->url_id);
        
        $this->assertTrue($item->isFirstItem());
    }
    
    public function testIsLstItem() {
        
        $item = new UrlSegmentItem('test', $this->url_id);
        
        $this->assertTrue($item->isLastItem());
    }
    
    public function testGetBelow() {
        
        $item = new UrlSegmentItem('test', $this->url_id);
        
        $this->assertEmpty($item->getBelow());
    }
    
    public function testGetAbove() {
        
        $item = new UrlSegmentItem('test', $this->url_id);
        
        $this->assertEmpty($item->getAbove());
    }
    
    public function testAttachSegmentItemAbove() {
        
        $above  = new UrlSegmentItem('bar', $this->url_id);
        $item   = new UrlSegmentItem('foo', $this->url_id);
        
        $item->attachSegmentItemAbove($above);
        
        $this->assertFalse($item->isLastItem());
        $this->assertTrue($item->isFirstItem());
        $this->assertInstanceOf('\Dbrouter\Url\Segment\UrlSegmentItem', $item->getAbove());
        $this->assertEmpty($item->getBelow());
    }
    
    public function testAttachItemAnalyzer() {
        
        $analyzer = m::mock('\Dbrouter\Url\Segment\UrlSegmentAnalyzer');
        $analyzer->shouldReceive('process')->once();
        
        $item   = new UrlSegmentItem('foo', $this->url_id);
        $item->attachAnalyzer($analyzer);        
    }
    
    public function testGetTypeWithoutAnalyzer() {
        
        $item   = new UrlSegmentItem('foo', $this->url_id);
        
        $this->assertEquals(NULL, $item->getType()); 
    }
    
    public function testGetTypePath() {
        
        $analyzer = m::mock('\Dbrouter\Url\Segment\UrlSegmentAnalyzer');
        $analyzer->shouldReceive('process')->once();
        $analyzer->shouldReceive('isPlaceholder')->andReturn(false);
        $analyzer->shouldReceive('isWildcard')->andReturn(false);
        $analyzer->shouldReceive('isFile')->andReturn(false);
        
        $item   = new UrlSegmentItem('foo', $this->url_id);
        $item->attachAnalyzer($analyzer);
        
        $this->assertEquals(UrlSegmentItem::TYPE_PATH, $item->getType());
    }
    
    public function testGetTypePlaceholder() {
        
        $analyzer = m::mock('\Dbrouter\Url\Segment\UrlSegmentAnalyzer');
        $analyzer->shouldReceive('process')->once();
        $analyzer->shouldReceive('isPlaceholder')->andReturn(true);
        $analyzer->shouldReceive('isWildcard')->andReturn(false);
        $analyzer->shouldReceive('isFile')->andReturn(false);
        
        $item   = new UrlSegmentItem('{foo}', $this->url_id);
        $item->attachAnalyzer($analyzer);
        
        $this->assertEquals(UrlSegmentItem::TYPE_PLACEHOLDER, $item->getType());
    }
    
    public function testGetTypeWildcard() {
        
        $analyzer = m::mock('\Dbrouter\Url\Segment\UrlSegmentAnalyzer');
        $analyzer->shouldReceive('process')->once();
        $analyzer->shouldReceive('isPlaceholder')->andReturn(false);
        $analyzer->shouldReceive('isWildcard')->andReturn(true);
        $analyzer->shouldReceive('isFile')->andReturn(false);
        
        $item   = new UrlSegmentItem('*', $this->url_id);
        $item->attachAnalyzer($analyzer);
        
        $this->assertEquals(UrlSegmentItem::TYPE_WILDCARD, $item->getType());
    }
    
    public function testGetTypeFile() {
        
        $analyzer = m::mock('\Dbrouter\Url\Segment\UrlSegmentAnalyzer');
        $analyzer->shouldReceive('process')->once();
        $analyzer->shouldReceive('isPlaceholder')->andReturn(false);
        $analyzer->shouldReceive('isWildcard')->andReturn(false);
        $analyzer->shouldReceive('isFile')->andReturn(true);
        
        $item   = new UrlSegmentItem('test.png', $this->url_id);
        $item->attachAnalyzer($analyzer);
        
        $this->assertEquals(UrlSegmentItem::TYPE_FILE, $item->getType());
    }
}
