<?php namespace Dbrouter\Url\Segment;

/**
 * Segment extentsion interface
 * Defines the supported file extentsion and neccessary methods.
 *
 * @package    Dbrouter
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2015 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
interface PlaceholderTypeInterface
{
    /**
     * Checks if the given placeholder type is already defined
     *
     * @param string $type
     * @return boolean
     */
    public function typeExists($type);

    /**
     * Sets the corresponding placeholder type
     *
     * @param   string $type
     * @return  PlaceholderTypeInterface
     */
    public function setType($type);

    /**
     * Returns the current type.
     *
     * @return string
     */
    public function getType();

    /**
     * Returns the corresponding type ID
     *
     * @return interger
     */
    public function getTypeId();

    /**
     * Overwrites the defined regex with the given one.
     *
     * @param   string $regex
     * @return  PlaceholderTypeInterface
     */
    public function setRegex($regex);

    /**
     * Returns a defined regex for the current type
     *
     * @return  string
     */
    public function getRegex();
}