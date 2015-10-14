<?php namespace Dbrouter\Cache\Driver;

/**
 * Base cache driver.
 *
 * @package    Dbrouter
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
abstract class BaseDriver
{
    /**
     * Connection parameter
     *
     * @var array
     */
    protected $params       = array();

    /**
     * Connection
     *
     * @var resource|object
     */
    protected $connection   = null;

    /**
     * Returns the connection parameter
     *
     * @return array
     */
    public function getConnectionParams()
    {
        return $this->params;
    }

    /**
     * Set the connection parameter
     *
     * @param array $params
     * @return $this
     */
    public function setConnectionParams(array $params)
    {
        $this->params = $params;

        // Return self for chaining

        return $this;
    }

    /**
     * Returns the cache connection
     *
     * @return resource|object
     */
    public function getConnection()
    {
        if (empty($this->connection)) {
            $this->initializeConnection($this->params);
        }

        return $this->connection;
    }

    /**
     * Initialize the cache connection
     *
     * @param   array $params
     * @return  void
     */
    abstract protected function initializeConnection(array $params);
}