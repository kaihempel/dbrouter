<?php namespace Dbrouter\Database;

use Dbrouter\Exception\Database\ConnectionFactoryException;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDOMySql\Driver;

/**
 * ConnectionFactory class
 *
 * @package    Dynamicuri
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class ConnectionFactory
{
    /**
     * Creates the Doctrine DBAL database connection instance
     *
     * @param array $params
     * @return Connection
     */
    public static function make(array $params)
    {
        if (empty($params)) {
            throw ConnectionFactoryException::make('No connection parameter given!');
        }

        // Check each parameter

        if ( ! isset($params['host']) || empty($params['host'])) {
            throw ConnectionFactoryException::make('No host given!');
        }

        if ( ! isset($params['user']) || empty($params['user'])) {
            throw ConnectionFactoryException::make('No user given!');
        }

        if ( ! isset($params['password']) || empty($params['password'])) {
            throw ConnectionFactoryException::make('No password given!');
        }

        if ( ! isset($params['dbname']) || empty($params['dbname'])) {
            throw ConnectionFactoryException::make('No database name given!');
        }

        // No port: try the default

        if (isset($params['port']) || empty($params['port'])) {
            $params['port'] = '3306';
        }

        // Create the connection instance

        $db = new Connection($params, new Driver());
        $db->connect();

        return $db;
    }
}