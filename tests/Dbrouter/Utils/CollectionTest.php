<?php namespace Dbrouter\Utils;

use PHPUnit_Framework_TestCase;

/**
 * Simple collection object.
 *
 * @package    Dbrouter
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class CollectionTest extends PHPUnit_Framework_TestCase
{

    public function testNewCollection()
    {
        $collection = new Collection(array('test1' => 'test1_data'));

        $this->assertInstanceOf('\Dbrouter\Utils\Collection', $collection);
        $this->assertEquals('test1_data', $collection['test1']);

        $collection['test2'] = 'test2_data';

        $this->assertEquals('test1_data', $collection['test1']);
    }

}