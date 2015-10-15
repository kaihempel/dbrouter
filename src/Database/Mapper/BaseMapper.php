<?php namespace Dbrouter\Database\Mapper;

use Dbrouter\Exception\Database\MapperException;
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
     *
     * @var array
     */
    protected $map = array();

    /**
     * Constructor
     *
     * @param Connection            $db             Database connection
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
     * @param Connection            $db             Database connection
     * @return void
     */
    abstract protected function load(Connection $db);

    /**
     * Executes the query and stores the data
     *
     * @param   Connection          $db             Database connection
     * @param   string              $query          Database query
     * @return  array
     * @throw   MapperException
     */
    protected function executeQuery(Connection $db, $query)
    {
        // Execute query and load data

        $data = $db->fetchAll($query);

        if (empty($data)) {
            throw MapperException::make('No data loaded!');
        }

        return $data;
    }

    /**
     * Checks if the map is empty
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return (empty($this->map));
    }

    /**
     * Returns the mapped data value to the given key
     *
     * @param   string|integer      $key            Current data key
     * @return  mixed|null
     * @throw   MapperException
     */
    public function getValue($key)
    {
        if ( ! is_string($key) && ! is_numeric($key)) {
            throw MapperException::make('Unsporrted value type given!');
        }
        
        if (isset($this->map[$key])) {
            return $this->map[$key];
        }

        return null;
    }

    /**
     * Set one value in current class map
     *
     * @param   string|integer      $key            Current data key
     * @param   string|integer      $value          Current data
     * @return  void
     */
    public function setValue($key, $value)
    {
        $this->map[$key] = $value;
    }

}