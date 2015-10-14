<?php namespace Dbrouter\Cache\Driver;

use Dbrouter\Database\ConnectionFactory;
use Dbrouter\Exception\Cache\DriverException;
use Carbon\Carbon;
use PDO;
use Exception;

/**
 * Database cache driver.
 *
 * @package    Dbrouter
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class DatabaseDriver extends BaseDriver implements DriverInterface
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'dbr_cache';

    /**
     * Constructor
     *
     * @param string $table
     */
    public function __construct($params, $table = '')
    {
        $this->setConnectionParams($params);
        $this->initializeConnection($this->params);

        if (empty($table)) {
            $this->setTable($table);
        }

    }

    /**
     * Initialize the cache connection
     *
     * @param   array       $params         Connection parameter
     * @return  void
     */
    protected function initializeConnection(array $params)
    {
        try {
            $this->connection = ConnectionFactory::make($params);

        } catch (Exception $e) {
            throw DriverException::make('Driver initialization failed: "' . $e->getMessage() . '"!');
        }

    }

    /**
     * Sets the database table.
     *
     * @param string $table
     * @return DatabaseDriver
     */
    public function setTable($table)
    {
        if ( ! $this->tableExists($table)) {
            throw DriverException::make('Database: Given table "' . $table . '" doesn\'t exists!');
        }

        // Save table as quoted string

        $this->table = $this->connection->quote($table);

        // Return self for chaining

        return $this;
    }

    /**
     * Checks the persistent cached data at once.
     *
     * @return  integer
     */
    public function revalidate()
    {
        // Delete all expired entries

        return $this->connection->executeUpdate(
            'DELETE FROM ' . $this->table . ' WHERE expire <= ?',
            array(Carbon::now()->toRfc822String()),
            array(PDO::PARAM_STR)
        );
    }

    /**
     * Returns the cache entry value to the corresponding.
     * Should by used in the store object.
     *
     * @param   string      $key
     */
    public function get($key)
    {
        return $this->connection->fetchColumn(
            'SELECT value FROM ' . $this->table . ' WHERE key = ?',
            array($key),
            0,
            array(PDO::PARAM_STR)
        );
    }

    /**
     * Puts one cache entry into database
     *
     * @param   string      $key
     * @param   string      $value
     * @param   integer     $minutes
     * @return  void
     */
    public function put($key, $value, $minutes)
    {
        $expires = Carbon::now()->addMinutes($minutes);

        return $this->connection->executeUpdate(
            'INSERT INTO ' . $this->table . ' (' . $this->connection->quote($key) . ', expire)' .
            ' VALUES (?, ?) ON DUPLICATE KEY UPDATE expire = ?',
            array((string)$value, $expires->toRfc822String(), $expires->toRfc822String()),
            array(PDO::PARAM_STR, PDO::PARAM_STR, PDO::PARAM_STR)
        );
    }

    /**
     * Checks if the given key is already valid.
     * The "get" method should im
     *
     * @param   string      $key
     * @return  boolean
     */
    public function validate($key)
    {
        $expire = $this->connection->fetchColumn(
            'SELECT expire FROM ' . $this->table . ' WHERE key = ?',
            array($key),
            0,
            array(PDO::PARAM_STR)
        );

        // Try to convert "expire" into a date object

        try {
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $expire);

        } catch (Exception $e) {
            return false;
        }

        // Compare expire date against current time

        return ($date->lt(Carbon::now())) ? false : true;
    }

    /**
     * Checks if a key already exists.
     *
     * @param   string      $key
     * @return  boolean
     */
    public function exists($key)
    {
        return ($this->get($key) == null) ? false : true;
    }

    /**
     * Remove an item from the cache.
     *
     * @param   string      $key
     * @return  void
     */
    public function forget($key)
    {
        $this->connection->delete(
            $this->table,
            array('key' => $key),
            array('key' => PDO::PARAM_STR)
        );
    }

    /**
     * Clears the whole cache
     *
     * @return  void
     */
    public function flush()
    {
        $this->connection->executeQuery('TRUNCATE ' . $this->table);
    }

    /**
     * Returns the whole item count.
     * Should called after "revalidate" or maybe should use the method to clear
     * expired items first.
     *
     * @return  integer
     */
    public function getItemCount()
    {
        $this->revalidate();

        return $this->connection->fetchColumn('SELECT count(*) FROM ' . $this->table);
    }

    /**
     * Checks if the given tables exists.
     *
     * @param   string $table
     * @return  boolean
     */
    private function tableExists($table)
    {
        $exists = $this->connection->fetchColumn(
            'SHOW TABLES LIKE ?',
            array($table),
            0,
            array(PDO::PARAM_STR)
        );

        return (!empty($exists)) ? true : false;
    }
}