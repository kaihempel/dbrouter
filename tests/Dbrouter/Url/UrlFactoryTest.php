<?php namespace Dbrouter\Url;

use PHPUnit_Framework_TestCase;
use Mockery as m;

/**
 * Url factory test
 *
 * @package    Dbrouter
 * @subpackage tests
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class UrlFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Dbrouter\Exception\Url\UrlException
     * @expectedExceptionMessage Missing or unecpected url value!
     */
    public function testUrlException() {
        $url = UrlFactory::make(array());
    }

    public function testCreateOnlyWithUrl()
    {
        $url = UrlFactory::make(array('url' => 'test/the/factory.html'));

        $this->assertInstanceOf('Dbrouter\Url\Url', $url);
        $this->assertEquals('test/the/factory.html', $url->getRawUrl());
    }

    public function testCreateWithData()
    {
        $urlId = m::mock('Dbrouter\Url\UrlIdentifier');
        $urlId->shouldReceive('getId')->once()->andReturn(1);

        $item1 = m::mock('Dbrouter\Url\Segment\UrlSegmentItem');
        $item1->shouldReceive('isFirstItem')->once()->andReturn(true);

        $item2 = m::mock('Dbrouter\Url\Segment\UrlSegmentItem');

        // Item one normaly should return item two. But in this case
        // getAbove should not becalled!

        $item1->shouldReceive('getAbove')->never();

        $data = array();
        $data['url']            = 'test/path/';
        $data['urlId']          = $urlId;
        $data['segments']       = $item1;
        $data['segmentcount']   = 2;
        $data['weight']         = 6;
        $data['usesPlaceholder']= false;
        $data['usesWildcard']   = false;

        $url = UrlFactory::make($data);

        $this->assertInstanceOf('Dbrouter\Url\Url', $url);
        $this->assertEquals('test/path/', $url->getRawUrl());
        $this->assertInstanceOf('Dbrouter\Url\UrlIdentifier', $url->getId());
        $this->assertEquals(1, $url->getId()->getId());
        $this->assertEquals(2, $url->getSegmentcount());
        $this->assertEquals(6, $url->getWeight());
        $this->assertFalse($url->usesPlaceholder());
        $this->assertFalse($url->usesWildcard());
    }
}