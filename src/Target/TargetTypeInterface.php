<?php namespace Dbrouter\Target;

/**
 * Target type interface.
 *
 * @package    Dbrouter
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
interface TargetTypeInterface
{
    const TARGETTYPE_CLOSURE    = 'closure';
    const TARGETTYPE_CLASS      = 'class';
    const TARGETTYPE_METHOD     = 'method';

    /**
     * Returns the type of the target
     *
     * @return  string
     */
    public function getType();

    /**
     * Sets the target type
     *
     * @param   string $type
     * @return  void
     */
    public function setType($type);

}