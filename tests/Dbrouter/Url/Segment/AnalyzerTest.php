<?php namespace Dbrouter\Url\Segment;

use PHPUnit_Framework_TestCase;
use Mockery as m;

/**
 * Url segment analyzer test
 *
 * @package    Dbrouter
 * @subpackage tests
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class AnalyzerTest extends PHPUnit_Framework_TestCase
{
    private $item = NULL;

    public function setUp() {
        $this->item = m::mock('\Dbrouter\Url\Segment\UrlSegmentItem');
    }

    public function testNewAnalyzer() {

        $this->item->shouldReceive('getValue')->andReturn('test');

        $analyzer = new UrlSegmentAnalyzer();
        $analyzer->process($this->item);

        $this->assertInstanceOf('\Dbrouter\Url\Segment\Analyzer', $analyzer);
        $this->assertEquals('path', $analyzer->getType());
        $this->assertEquals(2, $analyzer->getWeight());
    }

    public function testNewWildcard() {

        $this->item->shouldReceive('getValue')->andReturn('*');

        $analyzer = new UrlSegmentAnalyzer();

        $this->assertFalse($analyzer->isWildcard());

        $analyzer->process($this->item);

        $this->assertInstanceOf('\Dbrouter\Url\Segment\Analyzer', $analyzer);
        $this->assertTrue($analyzer->isWildcard());
        $this->assertFalse($analyzer->isPlaceholder());
        $this->assertFalse($analyzer->hasExtentsion());
        $this->assertEquals('wildcard', $analyzer->getType());
        $this->assertEquals(0, $analyzer->getWeight());
    }

    public function testNewPlaceholder() {

        $this->item->shouldReceive('getValue')->andReturn('{id}');

        $analyzer = new UrlSegmentAnalyzer();
        $analyzer->process($this->item);

        $this->assertInstanceOf('\Dbrouter\Url\Segment\Analyzer', $analyzer);
        $this->assertFalse($analyzer->isWildcard());
        $this->assertTrue($analyzer->isPlaceholder());
        $this->assertFalse($analyzer->hasExtentsion());
        $this->assertEquals('(.\*?)', $analyzer->getRegex());
        $this->assertEquals('id', $analyzer->getPlaceholderName());
        $this->assertEquals('placeholder', $analyzer->getType());
        $this->assertEquals(1, $analyzer->getWeight());
    }

    public function testNewWithExtentsion() {

        $this->item->shouldReceive('getValue')->andReturn('test.jpeg');

        $analyzer = new UrlSegmentAnalyzer();
        $analyzer->process($this->item);

        $this->assertInstanceOf('\Dbrouter\Url\Segment\Analyzer', $analyzer);
        $this->assertFalse($analyzer->isWildcard());
        $this->assertFalse($analyzer->isPlaceholder());
        $this->assertTrue($analyzer->hasExtentsion());
        $this->assertEquals('jpeg', $analyzer->getExtentsion());
        $this->assertTrue($analyzer->isFile());
        $this->assertEquals('file', $analyzer->getType());
        $this->assertEquals(3, $analyzer->getWeight());
    }
}
