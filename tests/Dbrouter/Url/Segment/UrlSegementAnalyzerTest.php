<?php namespace Dbrouter\Url\Segment;

use PHPUnit_Framework_TestCase;
use Mockery as m;

class UrlSegmentAnalyzerTest extends PHPUnit_Framework_TestCase 
{
    private $item = NULL;
    
    public function setUp() {
        $this->item = m::mock('\Dbrouter\Url\Segment\UrlSegmentItem');
    }
    
    public function testNewAnalyzer() {
        
        $this->item->shouldReceive('getValue')->andReturn('test');
        
        $analyzer = new UrlSegmentAnalyzer($this->item);
        
        $this->assertInstanceOf('\Dbrouter\Url\Segment\UrlSegmentAnalyzer', $analyzer);
    }
    
    public function testNewWildcard() {
        
        $this->item->shouldReceive('getValue')->andReturn('*');
        
        $analyzer = new UrlSegmentAnalyzer($this->item);
        
        $this->assertInstanceOf('\Dbrouter\Url\Segment\UrlSegmentAnalyzer', $analyzer);
        $this->assertTrue($analyzer->isWildcard());
        $this->assertFalse($analyzer->isPlaceholder());
        $this->assertFalse($analyzer->hasExtentsion());
    }
    
    public function testNewPlaceholder() {
        
        $this->item->shouldReceive('getValue')->andReturn('{id}');
        
        $analyzer = new UrlSegmentAnalyzer($this->item);
        
        $this->assertInstanceOf('\Dbrouter\Url\Segment\UrlSegmentAnalyzer', $analyzer);
        $this->assertFalse($analyzer->isWildcard());
        $this->assertTrue($analyzer->isPlaceholder());
        $this->assertFalse($analyzer->hasExtentsion());
        $this->assertEquals('(.\*?)', $analyzer->getRegex());
        $this->assertEquals('id', $analyzer->getPlaceholderName());
    }
    
    public function testNewWithExtentsion() {
        
        $this->item->shouldReceive('getValue')->andReturn('test.jpeg');
        
        $analyzer = new UrlSegmentAnalyzer($this->item);
        
        $this->assertInstanceOf('\Dbrouter\Url\Segment\UrlSegmentAnalyzer', $analyzer);
        $this->assertFalse($analyzer->isWildcard());
        $this->assertFalse($analyzer->isPlaceholder());
        $this->assertTrue($analyzer->hasExtentsion());
        $this->assertEquals('jpeg', $analyzer->getExtentsion());
        
    }
}
