<?php namespace Dbrouter\Utils;

use ArrayObject;

/**
 * Simple collection object.
 *
 * @package    Dbrouter
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class Collection extends ArrayObject
{
    /**
     * Constructor
     *
     * @param   array $array
     * @return  void
     */
    public function __construct(array $array = array())
    {
        parent::__construct($array, ArrayObject::STD_PROP_LIST);
    }

}