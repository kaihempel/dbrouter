<?php namespace Dbrouter\Database\Mapper;

use Dbrouter\Database\Mapper\BaseMapper;
use Dbrouter\Exception\Database\MapperException;
use Doctrine\DBAL\Connection;

/**
 * Type mapper class.
 *
 * @package    Dbrouter
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2015 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class PlaceholderTypeMapper extends BaseMapper
{

    /**
     * Regex map
     *
     * @var array
     */
    protected $regexMap  = array();

    /**
     * Load the type mapping
     *
     * @param Connection $db
     */
    protected function load(Connection $db)
    {
        // Check if data already loaded

        if ( ! $this->isEmpty()) {
            return;
        }

        // Load data

        $data = $this->executeQuery($db, 'SELECT id, type, regex FROM dbr_placeholdertype');

        // Store data

        foreach ($data as $row) {
            $this->setValue($row->type, $row->id);
            $this->setRegex($row->id, $row->regex);
        }
    }

    /**
     * Return the ID of the item type
     *
     * @param   string|integer $type
     * @return  interger|null
     */
    public function getPlaceholderTypeId($type)
    {
        return $this->getValue($type);
    }

    /**
     * Sets the regex
     *
     * @param   integer             $id             Current data id
     * @param   string|integer      $regex          Current regex
     * @return  PlaceholderTypeMapper
     */
    public function setRegex($id, $regex)
    {
        if ( ! is_numeric($id)) {
            throw MapperException::make('Unsupported key!');
        }

        $this->regexMap[$id] = $regex;

        return $this;
    }

    /**
     * Returns the regex
     *
     * @param string $key
     * @return string
     */
    public function getRegex($key)
    {
        if ( ! is_numeric($key)) {
            $key = $this->getValue($key);
        }

        if(isset($this->regexMap[$key])) {
            return $this->regexMap[$key];
        }

        return null;
    }

}

