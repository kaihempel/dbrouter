<?php namespace Dbrouter\Database\Mapper;

use Dbrouter\Database\Mapper\BaseMapper;
use Dbrouter\Url\Segment\UrlSegmentItem;
use Doctrine\DBAL\Connection;

/**
 * Type mapper class.
 *
 * @package    Dbrouter
 * @author     Kai Hempel <dev@kuweh.de>
 * @copyright  2014 Kai Hempel <dev@kuweh.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       https://www.kuweh.de/
 * @since      Class available since Release 1.0.0
 */
class PlaceholderTypeMapper extends BaseMapper
{
    /**
     * Type map variable.
     *
     * @var array
     */
    protected static $map       = array();

    /**
     * Regex map
     *
     * @var array
     */
    protected static $regexMap  = array();

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
            $this->setValue($row->name, $row->id);
            $this->setRegex($row->id, $row->regex);
        }
    }

    /**
     * Return the ID of the item type
     *
     * @param   UrlSegmentItem $item
     * @return  interger|null
     */
    public function getPlaceholderTypeId(UrlSegmentItem $item)
    {
        return $this->getValue($item->getType());
    }

    /**
     * Sets the regex
     *
     * @param   string|integer      $key            Current data key
     * @param   string|integer      $regex          Current regex
     * @return  void
     */
    public function setRegex($key, $regex)
    {
        static::$regexMap[$key] = $regex;
    }

}

