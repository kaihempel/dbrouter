<?php namespace Dbrouter\Database;

use PHPUnit_Framework_TestCase;

/**
 * Url matcher test
 *
 * @package    Dbrouter
 * @subpackage tests
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class ConnectionFactoryTest extends PHPUnit_Framework_TestCase
{
    protected $params = array(
        'host'      => 'localhost',
        'user'      => 'test',
        'password'  => 'test',
        'dbname'    => 'test',
        'port'      => 3306
    );

    public function testNewConnection()
    {
        $db = ConnectionFactory::make($this->params);

        $this->assertInstanceOf('Doctrine\DBAL\Connection', $db);
    }

    /**
     * @expectedException \Dbrouter\Exception\Database\ConnectionFactoryException
     * @expectedExceptionMessage No connection parameter given!
     */
    public function testParamException()
    {
        $db = ConnectionFactory::make(array());
    }

    /**
     * @expectedException \Dbrouter\Exception\Database\ConnectionFactoryException
     * @expectedExceptionMessage No host given!
     */
    public function testNoHostException()
    {
        $this->params['host'] = null;
        $db = ConnectionFactory::make($this->params);
    }

    /**
     * @expectedException \Dbrouter\Exception\Database\ConnectionFactoryException
     * @expectedExceptionMessage No user given!
     */
    public function testNoUserException()
    {
        $this->params['user'] = null;
        $db = ConnectionFactory::make($this->params);
    }

    /**
     * @expectedException \Dbrouter\Exception\Database\ConnectionFactoryException
     * @expectedExceptionMessage No password given!
     */
    public function testNoPasswordException()
    {
        $this->params['password'] = null;
        $db = ConnectionFactory::make($this->params);
    }

    /**
     * @expectedException \Dbrouter\Exception\Database\ConnectionFactoryException
     * @expectedExceptionMessage No database name given!
     */
    public function testNoDatabaseException()
    {
        $this->params['dbname'] = null;
        $db = ConnectionFactory::make($this->params);
    }

    public function testNoPort()
    {
        $this->params['port'] = null;
        $db = ConnectionFactory::make($this->params);

        $this->assertInstanceOf('Doctrine\DBAL\Connection', $db);
    }
}