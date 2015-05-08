<?php namespace Dbrouter\Url\Segment;

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
class UrlSegmentParserTest extends PHPUnit_Framework_TestCase
{
    protected $url = NULL;
    
    public function setUp() {
        $this->url = m::mock('\Dbrouter\Url\Url');
    }
    
    public function testNewParser() {
        
        $parser = new UrlSegmentParser($this->url);
        
        $this->assertInstanceOf('\Dbrouter\Url\Segment\UrlSegmentParser', $parser);
    }
    
    public function testProcess() {
        
        $this->url->shouldReceive('getRawUrl')->once()->andReturn('/test/path/');
        $this->url->shouldReceive('getId')->once()->andReturn(NULL);
        $this->url->shouldReceive('attachUrlSegmentItem');
        
        $parser = new UrlSegmentParser($this->url);
        $parser->process();
        
        $this->assertInstanceOf('\Dbrouter\Url\Segment\UrlSegmentParser', $parser);
    }
    
    public function testProcessWithFile() {
        
        $this->url->shouldReceive('getRawUrl')->once()->andReturn('/test/test.html');
        $this->url->shouldReceive('getId')->once()->andReturn(NULL);
        $this->url->shouldReceive('attachUrlSegmentItem');
        
        $parser = new UrlSegmentParser($this->url);
        $parser->process();
        
        $this->assertInstanceOf('\Dbrouter\Url\Segment\UrlSegmentParser', $parser);
    }
    
    /**
     * @expectedException Dbrouter\Exception\Url\UrlException
     */
    public function testProcessEmptyItem() {
        
        $this->url->shouldReceive('getRawUrl')->once()->andReturn('/test////test.html');
        $this->url->shouldReceive('getId')->once()->andReturn(NULL);
        $this->url->shouldReceive('attachUrlSegmentItem');
        
        $parser = new UrlSegmentParser($this->url);
        $parser->process();
        
        $this->assertInstanceOf('\Dbrouter\Url\Segment\UrlSegmentParser', $parser);
    }
    
    /**
     * @expectedException Dbrouter\Exception\Url\UrlException
     */
    public function testProcessEmptyUrl() {
        
        $this->url->shouldReceive('getRawUrl')->once()->andReturn(NULL);
        $this->url->shouldReceive('getId')->once()->andReturn(NULL);
        $this->url->shouldReceive('attachUrlSegmentItem');
        
        $parser = new UrlSegmentParser($this->url);
        $parser->process();
        
        $this->assertInstanceOf('\Dbrouter\Url\Segment\UrlSegmentParser', $parser);
    }
}