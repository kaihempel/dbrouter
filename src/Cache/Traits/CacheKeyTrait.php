<?php namespace Dbrouter\Cache\Traits;

/**
 * Trait for central cache key definition
 *
 * @package    Dbrouter
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
trait CacheKey {

    /**
     * Generates the hash
     *
     * @param   string          $value          Value to generate the sha1 from.
     * @return  string
     */
    public function generateCacheKey($value) {

        if (empty($value)) {
            return sha1('');
        }

        return sha1($value);
    }

    /**
     * Checks if given value is a valid cache key.
     *
     * @param   string          $value          Posible cache key.
     * @return  boolean
     */
    public function isCacheKey($value) {

        if (empty($value)) {
            return false;
        }

        if (preg_match('/^[a-f0-9]{40}$/i', $value)) {
            return true;
        }

        return false;
    }

}