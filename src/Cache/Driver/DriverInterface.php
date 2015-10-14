<?php namespace Dbrouter\Cache\Driver;

/**
 * Cache Driver Interface.
 * Defines the main methods for the driver store interaction.
 *
 * @package    Dbrouter
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
interface DriverInterface
{
    /**
     * Checks the persistent cached data at once.
     *
     * @return  void
     */
    public function revalidate();

    /**
     * Returns the cache entry value to the corresponding.
     * Should by used in the store object.
     *
     * @param   string      $key
     */
    public function get($key);

    /**
     * Puts one cache
     *
     * @param   string      $key
     * @param   string      $value
     * @param   string      $minutes
     * @return  void
     */
    public function put($key, $value, $minutes);

    /**
     * Checks if the given key is already valid.
     * The "get" method should im
     *
     * @param   string      $key
     * @return  boolean
     */
    public function validate($key);

    /**
     * Checks if a key already exists.
     *
     * @param   string      $key
     * @return  boolean
     */
    public function exists($key);

    /**
     * Remove an item from the cache.
     *
     * @param   string      $key
     * @return  void
     */
    public function forget($key);

    /**
     * Clears the whole cache
     *
     * @return  void
     */
    public function flush();

    /**
     * Returns the whole item count.
     * Should called after "revalidate" or maybe should use the method to clear
     * expired items first.
     *
     * @return  integer
     */
    public function getItemCount();
}