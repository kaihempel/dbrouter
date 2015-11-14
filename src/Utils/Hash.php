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
        $this->hash = $this->calculateHash($string);
    }

    /**
     * Calculates the SHA1 hash string
     *
     * @param string $string
     */
    private function calculateHash($string)
    {
        if ( ! is_string($string)) {
            $string = $this->generateHashableData($string);
        }

        return sha1($string);
    }

    /**
     * Generates a hashable string value based on the given data
     *
     * @param mixed $data
     * @return string
     */
    private function generateHashableData($data)
    {
        if (is_object($data)) {
            return spl_object_hash($data);

        } else if (is_array($data)) {
            return serialize($data);
        }

        return (string)$data;
    }

    /**
     *
     * @param type $string
     */
    public function resetHash($string)
    {
        $this->hash = $this->calculateHash($string);
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
     * Magic call for direct string compare
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getHash();
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
