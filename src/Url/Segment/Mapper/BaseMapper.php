<?php namespace Dbrouter\Url\Segment\Mapper;

use Doctrine\DBAL\Connection;
use PDO;

/**
 * Base mapper class.
 * Defines the basic mapper logic.
 *
 * @package    Dbrouter
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
abstract class BaseMapper
{
    /**
     * Base map variable.
     * Have to be redeclared in child class!
     *
     * @var array
     */
    protected static $map;

    /**
     * Constructor
     *
     * @param Connection $db
     */
    public function __construct(Connection $db)
    {
        // Set the PDO fetch mode

        $db->setFetchMode(PDO::FETCH_OBJ);

        // Load the data

        $this->load($db);
    }

    /**
     * Loads the mapping data
     *
     * @param Connection $db
     * @return void
     */
    abstract protected function load(Connection $db);

    /**
     *
     * @return type
     */
    public function isEmpty()
    {
        return (empty(static::$map)) ? true : false;
    }

    /**
     * Returns the mapped data value to the given key
     *
     * @param   string|integer $key
     * @return  mixed|null
     */
    public function getValue($key)
    {
        if (isset(static::$map[$key])) {
            return static::$map[$key];
        }

        return NULL;
    }

    /**
     *
     * @param type $key
     * @param type $value
     */
    public function setValue($key, $value)
    {
        static::$map[$key] = $value;
    }

}