<?php namespace Dbrouter\Url;

use PHPUnit_Framework_TestCase;

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
class UrlIdentifierTest extends PHPUnit_Framework_TestCase 
{
    public function testNewUrlIdentifier() {
        $id = new UrlIdentifier(1);
        
        $this->assertInstanceOf('Dbrouter\Url\UrlIdentifier', $id);
        $this->assertEquals(1, $id->getId());
    }
    
    /**
     * @expectedException \Dbrouter\Exception\Url\UrlIdentifierException
     * @expectedExceptionMessage Unexpected ID value given!
     */
    public function testNewUrlIdentifierException() {
        $id = new UrlIdentifier(NULL);
    }
}
