<?php namespace Dbrouter\Cache\CacheKey;

use Dbrouter\Cache\Driver\DriverInterface;
use Dbrouter\Utils\Hash;

/**
 * Cache key
 *
 * @package    Dbrouter
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class CacheKey extends Hash
{
    /**
     * Checks if the cache key already exists
     *
     * @param DriverInterface $driver
     * @return type
     */
    public function exists(DriverInterface $driver)
    {
        return $driver->exists($this->hash);
    }
}