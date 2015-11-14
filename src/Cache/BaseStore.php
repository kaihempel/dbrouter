<?php namespace Dbrouter\Cache;

use Dbrouter\Cache\Driver\DriverInterface;

/**
 * Base cache store
 *
 * @package    Dbrouter
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
abstract class BaseStore
{
    /**
     * Cache driver
     *
     * @var type
     */
    protected $driver = null;

    /**
     * Constructor
     *
     * @param   DriverInterface $driver
     * @return  void
     */
    public function __construct(DriverInterface $driver)
    {
        $this->setCacheDriver($driver);
    }

    /**
     * Sets the cache driver
     *
     * @param   DriverInterface $driver
     * @return  BaseStore
     */
    public function setCacheDriver(DriverInterface $driver)
    {
        $this->driver = $driver;

        // Return self for chaining

        return $this;
    }

    /**
     * Returns the current cache driver
     *
     * @return DriverInterface
     */
    public function getCacheDriver()
    {
        return $this->driver;
    }
}