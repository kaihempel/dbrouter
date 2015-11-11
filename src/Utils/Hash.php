<?php namespace Dbrouter\Utils;

/**
 * Simple hash object.
 * Generates a sha1 hash of the given string
 *
 * @package    Dbrouter
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class Hash {

    /**
     * Current hash string
     *
     * @var string
     */
    protected $hash = NULL;

    /**
     * Constructor
     *
     * @param stdClass $data
     */
    public function __construct($string)
    {
        $this->calculateHash($string);
    }

    /**
     * Calculates the SHA1 hash string
     *
     * @param string $string
     */
    private function calculateHash($string)
    {
        $this->hash = sha1($string);
    }

    /**
     * Returns the hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Checks if the given string is a valid hash
     *
     * @param string $hash
     * @return boolean
     */
    public static function valid($hash)
    {

        if (empty($hash) || ! is_string($hash) || preg_match('/^[a-f0-9]{40}/i', $hash) == false) {
            return false;
        }

        return true;
    }

}
